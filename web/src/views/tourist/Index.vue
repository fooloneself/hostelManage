<style scoped>
.carousel{
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    color: #ffffff;
    z-index: 10;
    overflow: hidden;
    .carousel-list{
    	background: url('/src/images/bg.jpg') 50% 0% no-repeat;
    	height: 1000px;
    }
}
</style>

<template>
	<Carousel class="carousel" v-model="value" autoplay autoplay-speed="3000" loop>
        <CarouselItem>
            <div class="carousel-list"></div>
        </CarouselItem>
        <CarouselItem>
            <div class="carousel-list"></div>
        </CarouselItem>
        <CarouselItem>
            <div class="carousel-list"></div>
        </CarouselItem>
        <CarouselItem>
            <div class="carousel-list"></div>
        </CarouselItem>
    </Carousel>
</div>
</template>

<script>
export default{
    data () {
        return {
        	value:0
        }
    }
}
</script>
