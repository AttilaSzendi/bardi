<template>
    <div>
        <p class="alert-info">Egyszerre csak {{ maxSelection }} szék foglalható!</p>
        <div id="room" style="display: flex">
            <div class="seat" v-for="item in seats" :key="item.id" @click="select(item.id)" v-bind:class="{ selected: isSelected(item.id) }">
                {{ item.id }}
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
                maxSelection: 2
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
                return this.selectedSeats.includes(seatId);
            }
        }
    }
</script>
