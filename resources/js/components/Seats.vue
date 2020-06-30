<template>
    <div>
        <div v-if="selecting">
            <p class="alert-info">Egyszerre csak {{ maxSelection }} szék foglalható!</p>
            <div id="room" style="display: flex">
                <div class="seat" v-for="item in seats" :key="item.id"
                     @click="select(item)"
                     v-bind:class="{ selected: isSelected(item.id), reserved: item.statusId === 2, paid: item.statusId === 3 }">
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
                <p>2 perced van a vásárlás befejezéséig!</p>
                <button class="btn btn-danger" @click="cancel">Mégsem</button>
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
            select(seat) {
                if(seat.statusId !== 1){
                    return
                }

                if(this.selectedSeats.includes(seat.id)){
                    const index = this.selectedSeats.indexOf(seat.id)
                    if (index > -1) {
                        this.selectedSeats.splice(index, 1)
                    }
                    return
                }

                if(this.selectedSeats.length === this.maxSelection){
                    return
                }
                this.selectedSeats.push(seat.id)
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

                        this.selectedSeats.forEach(value => {
                            this.seats.find(item => item.id === value).statusId = 2
                        });
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

                        this.selectedSeats.forEach(value => {
                            this.seats.find(item => item.id === value).statusId = 3
                        });

                        this.selectedSeats = [];
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
