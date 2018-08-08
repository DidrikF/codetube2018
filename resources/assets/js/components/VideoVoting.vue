<template>
	
	<div class="video_voting">
		<a href="#" class="video__voting-button" v-bind:class="{'video__voting-button--voted': userVote == 'up'}" @click.prevent="vote('up')">
			<span class="glyphicon glyphicon-thumbs-up"></span>
		</a> {{ up }} &nbsp;

		<a href="#" class="video__voting-button" v-bind:class="{'video__voting-button--voted': userVote == 'down'}" @click.prevent="vote('down')">
			<span class="glyphicon glyphicon-thumbs-down"></span>
		</a> {{ down }}
	</div>


</template>

<script>
	import axiosInstance from '../axiosInstance';
	export default {
		data () {
			return {
				up: 0,
				down: 0,
				userVote: null,
				canVote: false
			}
		},

		methods: {
			getVotes () {
				axiosInstance.get('/videos/' + this.videoUid + '/votes').then((response) => {
					this.up = response.data.data.up ? response.data.data.up : 0;
					this.down = response.data.data.down ? response.data.data.down : 0;
					this.userVote = response.data.data.user_vote;
					this.canVote = response.data.data.can_vote;
				});
			},


			vote (type) {
				if(this.userVote == type) {
					this[type]--; 					//this[up]
					this.userVote = null;
					this.deleteVote(type);
					return;
				}

				if(this.userVote){ //remove the oposite vote
					this[type == 'up' ? 'down' : 'up']--;
				}

				this[type]++;

				this.userVote = type;

				this.createVote(type);
			},

			deleteVote (type) {
				axiosInstance.delete('/videos/' + this.videoUid + '/votes')
					.catch(() => {
						console.log('Users needs to be logged in to cast votes.')
					});
			},

			createVote (type) {
				axiosInstance.post('/videos/' + this.videoUid + '/votes', {
					type: type
				}).catch(() => {
					console.log('Users needs to be logged in to cast votes.')
				});
			},
		},

		props: {
			videoUid: null
		},

		mounted(){
			this.getVotes();
		}

	}

</script>