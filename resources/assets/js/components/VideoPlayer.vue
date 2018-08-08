<!-- This file represent the <video-player> vue componenet, that we put on our page just like any other html element -->
<template>
	
	<!-- we are setting properties on the (video.js) element which vidoe.js will enterperate -->
	<video 
		id="video" 
		class="video-js vjs-default-skin vjs-big-play-centered vjs-16-9" 
		controls 
		preload="auto" 
		data-setup='{"fluid": true, "preload": "auto"}'
		v-bind:poster="thumbnailUrl"
		>

		<source type="video/mp4" v-bind:src="videoUrl"></source>

	</video>

</template>

<script>
	import videojs from "video.js";
	import axiosInstance from '../axiosInstance';
	export default { // data being exported to the calling script, when require is called
		data () {
			return {
				player: null,
				duration: 0,
			}
		},

		props: {
			//these values are being filled throug the html which is filled by the blade templating enine.
			videoUid: null,
			videoUrl: null,
			thumbnailUrl: null,
		},

		methods: {
			hasHitQuotaView () {
				if(!this.duration){
					return false;
				}
				return Math.round(this.player.currentTime()) === Math.round((10 * this.duration) / 100); //has the video played 10% of total time?
			},

			createView () {
				axiosInstance.post('/videos/' + this.videoUid + '/views');
			}

		},

		mounted () {
			this.player = videojs('video')

			this.player.on('loadedmetadata', () => {
				this.duration = Math.round(this.player.duration());
			})

			setInterval(() => {
				if(this.hasHitQuotaView()) {
					this.createView();
				}
			}, 1000)
		}
	}

</script>