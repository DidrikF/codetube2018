<template>
	<div  v-if="subscribers !== null"> <!-- class="subscribe-button" -->
		{{ subscribers }} {{ subscribers | pluralize('subscriber') }} &nbsp; 
		<button class="btn btn-xs btn-default" type="button" v-if="canSubscribe" @click.prevent="handle">{{ userSubscribed ? 'Unsubscribe' : 'Subscribe' }}</button>
	</div>
</template>



<script>
	import axiosInstance from '../axiosInstance';

	export default {
		data () {
			return {
				subscribers: null,
				userSubscribed: false,
				canSubscribe: false
			}
		},

		props: {
			channelSlug: null
		},

		methods: {
			getSubscriptionStatus () {
				axiosInstance.get('/subscription/' + this.channelSlug).then((response) => {
					this.subscribers = response.data.data.count;
					this.userSubscribed = response.data.data.user_subscribed;
					this.canSubscribe = response.data.data.can_subscribe;
				});
			},

			handle () {
				if(this.userSubscribed) {
					this.unsubscribe();
				} else {
					this.subscribe();
				}
			},

			subscribe () {
				this.userSubscribed = true; //quick UI
				this.subscribers++;
				axiosInstance.post('/subscription/' + this.channelSlug);
			},

			unsubscribe () {
				this.userSubscribed = false;
				this.subscribers--;
				axiosInstance.delete('/subscription/' + this.channelSlug);
			}
		},

		mounted () {
			this.getSubscriptionStatus();
		}
	}


</script>

<!-- This componenet can now be reused wherever we display channel information! -->