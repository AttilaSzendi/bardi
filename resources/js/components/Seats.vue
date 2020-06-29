<template>
    <div>
        <div v-if="selecting">
            <p class="alert-info">Egyszerre csak {{ maxSelection }} szék foglalható!</p>
            <div id="room" style="display: flex">
                <div class="seat" v-for="item in seats" :key="item.id" @click="select(item.id)" v-bind:class="{ selected: isSelected(item.id) }">
                    {{ item.id }}
                </div>
            </div>
            <button class="btn btn-primary" v-show="hasSelection" @click="reserve">Kiválaszt</button>
        </div>
        <div v-else>
            <div>
                A választott szék(ek): {{ selectedSeats }}
            </div>
            <div v-if="reserving">
                <div>
                    <label for="email">Email cím:</label>
                    <input id="email" type="text" name="email" placeholder="email" v-model="email" required>
                </div>


                <button class="btn btn-danger" @click="cancel">Mégsem</button>
                <button class="btn btn-primary" @click="sendReservation">Foglalás véglegesítése</button>
            </div>
            <div v-else>
                <p>hátralévő idő: 1perc, 23mp</p>
                <button class="btn btn-primary" @click="pay">Fizetés</button>
            </div>

            <div v-show="errorMessage !== ''">
                <p class="alert-danger">{{ errorMessage }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            axios.get('/api/seats')
                .then((response) => {
                    this.seats = response.data.data
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        data () {
            return {
                seats: [],
                selectedSeats: [],
                maxSelection: 2,
                selecting: true,
                reserving: true,
                email: '',
                errorMessage: '',
                reservationId: null,
            }
        },
        methods: {
            select(seatId) {
                if(this.selectedSeats.includes(seatId)){
                    const index = this.selectedSeats.indexOf(seatId)
                    if (index > -1) {
                        this.selectedSeats.splice(index, 1)
                    }
                    return
                }

                if(this.selectedSeats.length === this.maxSelection){
                    return
                }
                this.selectedSeats.push(seatId)
            },
            isSelected(seatId) {
                return this.selectedSeats.includes(seatId)
            },
            reserve() {
                this.selecting = false
            },

            sendReservation() {
                this.selecting = false

                axios.post('/api/reservations', {
                    email: this.email,
                    selectedSeats: this.selectedSeats
                })
                    .then((response) => {
                        this.reserving = false

                        this.reservationId = response.data.data.id
                    })
                    .catch((error) => {
                        if(error.response.data.errors.selectedSeats[0] !== 'undefined'){
                            this.errorMessage = error.response.data.errors.selectedSeats[0]
                        }
                    });

            },
            pay() {
                axios.put('/api/reservations/' + this.reservationId)
                    .then((response) => {
                        this.selecting = true
                        console.log('siker...')
                    })
                    .catch((error) => {
                    });
            },
            cancel() {
                this.selecting = true
                this.email = ''
                this.errorMessage = ''
            }
        },
        computed: {
            hasSelection(){
                return this.selectedSeats.length > 0;
            }
        }
    }
</script>
