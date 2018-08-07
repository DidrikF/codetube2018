<template>
<div>
	<p>{{ comments.length }} {{ comments.length | pluralize 'comment' }}</p> <!-- We need a dom element inside the template for it to work -->

	<div class="video-comment clearfix" v-if="$root.user.authenticated"> <!-- We passed in the user through the Auth fasade in app.blade.php -->
		<textarea placeholder="Say something" class="form-control video-comment__input" v-model="body"></textarea>
		<div class="pull-right">
			<button type="submit" class="btn btn-default" @click.prevent="createComment">Post</button> <!-- .prevent also prevents the page form jumping to the top -->
		</div>
	</div>

	<ul class="media-list">
		<!-- COMMENT -->
		<li class="media" v-for="comment in comments">
			<div class="media-left">
				<a :href="'/channel/' + comment.channel.data.slug">
					<img v-bind:src="comment.channel.data.image" :alt="comment.channel.data.image" class="media-object">
				</a>
			</div>
			<div class="media-body">
				<a :href="'/channel/' + comment.channel.data.slug">{{ comment.channel.data.name }}</a> {{ comment.created_at_human }}
				<p>{{ comment.body }}</p>

				<!-- REPLY TO COMMENT FORM --> 
				<ul class="list-inline" v-if="$root.user.authenticated">
					<li>
						<a href="#" @click.prevent="toggleReplyForm(comment.id)">{{ replyFormVisible === comment.id ? 'Cancel' : 'Reply' }}</a>
					</li>
					<!-- DELETE COMMENT -->
					<li>
						<a href="#" v-if="$root.user.id === comment.user_id" @click.prevent="deleteComment(comment.id)">Delete</a>
					</li>
				</ul>

				
				<div class="video-comment clear" v-if="replyFormVisible === comment.id">
					<textarea class="form-control video-comment__input" v-model="replyBody"></textarea>
					<div class="pull-right">
						<button type="submit" class="btn btn-default" @click.prevent="createReply(comment.id)">Post</button>
					</div>
				</div>

				<!-- REPLIES TO COMMENT -->
				<div class="media" v-for="reply in comment.replies.data"> <!-- what if no replies, you cannot evaluate this -->
					<div class="media-left">
						<a :href="'/channel/' + reply.channel.data.slug">
							<img v-bind:src="reply.channel.data.image" :alt="reply.channel.data.image" class="media-object">
						</a>
					</div>
					<div class="media-body">
						<a :href="'/channel/' + reply.channel.data.slug">{{ reply.channel.data.name }}</a> {{ reply.created_at_human }}
						<p>{{ reply.body }}</p>

						<ul class="list-inline" v-if="$root.user.authenticated">
							
							<!-- DELETE REPLY -->
							<li>
								<a href="#" v-if="$root.user.id === reply.user_id" @click.prevent="deleteComment(reply.id)">Delete</a>
							</li>
						</ul>
					</div>
				</div>


			</div>
		</li>		
	</ul>
</div>
</template>

<script>
	
	export default {

		data () {
			return {
				comments: [], //holds "/videos/15824a56bedc73/comments" json response data in an array comment[2].replies.data references the data of a comment reply
				body: null, //Value are dymaically updated as the user updates the value, tagging body as a v-model in the textarea for commenting
				replyBody: null,
				replyFormVisible: null
			}
		},

		props: {
			videoUid: null
		},

		methods: {

			toggleReplyForm(commentId) {
				this.replyBody = null;
				
				if (this.replyFormVisible === commentId) { //untogle the reply form on a comment, if that was already selected
					this.replyFormVisible = null;
					return;
				}

				this.replyFormVisible = commentId; //otherwise, just toggle the reply form on the new comment
			},

			createReply(commentId) {
				this.$http.post('/videos/' + this.videoUid + '/comments', {
					body: this.replyBody,
					reply_id: commentId
				}).then((response) => {
					this.comments.map((comment, index) => {
						if(comment.id === commentId) {
							this.comments[index].replies.data.push(response.json().data); //pushing the response (comment reply data) on the comment
							return; //kill the loop when after finding where to insert the reply
						}
					});
					this.replyBody = null;
					this.replyFormVisible = null;
				});
			},

			createComment() {
				this.$http.post('/videos/' + this.videoUid + '/comments', {
					body: this.body
				}).then((response) => {
					this.comments.unshift(response.json().data);
					this.body = null;
				});
			},

			getComments () {
				this.$http.get('/videos/' + this.videoUid + '/comments').then((response) => {
					this.comments = response.json().data;
				});
			},

			deleteComment(commentId) {
				if (!confirm('Are you sure you want to delete this comment?')) {
					return;
				}

				this.deleteById(commentId); //what does this do 
				this.$http.delete('/videos/' + this.videoUid + '/comments/' + commentId);
			},

			//remove from the dom
			deleteById (commentId) {
				this.comments.map((comment, index) => {
					if (comment.id === commentId) {
						this.comments.splice(index, 1);
						return;
					}

					comment.replies.data.map((reply, replyIndex) => {
						if(reply.id === commentId) {
							this.comments[index].replies.data.splice(replyIndex, 1);
							return;
						}
					});
				});
			}

		},


		ready() {
			this.getComments();
		}

	}

</script>