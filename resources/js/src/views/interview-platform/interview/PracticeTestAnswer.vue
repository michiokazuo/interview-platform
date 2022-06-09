<template>
	<!-- question-test -->
	<b-row
		v-if="id && interview"
		class="blog-list-wrapper match-height"
	>
		<b-col cols="12">
			<b-link
				v-if="interview.record && userOn && (interview.record.candidate || interview.record.company)"
				:to="interview.record.company ? interview.record.company : interview.record.candidate"
				class="font-weight-bold mb-2"
				target="_blank"
			>
				<b-button
					v-ripple.400="'rgba(255, 255, 255, 0.15)'"
					variant="primary"
					class="mb-2"
				>
					View record
				</b-button>
			</b-link>
		</b-col>
		<template v-if="interview.result && interview.result.candidate">
			<b-col
				v-for="question in questions"
				:key="question.id"
				md="6"
			>
				<b-card
					tag="article"
					no-body
				>
					<b-card-body class="d-flex justify-content-between flex-column">
						<b-card-title>
							<div
								class="mail-message"
								v-html="question.title"
							/>
              <div class="my-1 py-25 h6">
							<b-link
								v-for="(tag,index) in question.tags"
								:key="index"
							>
								<b-badge
									pill
									class="mr-75"
									:variant="tagsColor(tag.name)"
								>
									{{ tag.name }}
								</b-badge>
							</b-link>
						</div>
						</b-card-title>
						<b-media no-body>
							<b-media-body>
								<div
									class="mail-message blog-content-truncate"
									v-html="question.content"
								/>
							</b-media-body>
						</b-media>
						
						<div class="d-flex flex-wrap justify-content-between align-items-center">
              <hr class="w-100">
							<b-link>
								<div class="d-flex align-items-center text-body">
									<feather-icon
										icon="MessageSquareIcon"
										class="mr-50"
									/>
								</div>
							</b-link>
							<b-link
								class="font-weight-bold"
								@click.prevent="setQA(question)"
							>
								Details
							</b-link>
						</div>
					</b-card-body>
				</b-card>
			</b-col>
			<b-col cols="12">
				<!-- pagination -->
				<div class="my-2">
					<b-pagination-nav
						v-model="currentPage"
						align="center"
						:number-of-pages="rows"
						class="mb-0"
						base-url="#"
					/>
				</div>
			</b-col>
		</template>
    <b-col cols="12" v-else>
			<b-card
				no-body
				class="faq-search pt-5 pb-5"
				:style="{backgroundImage:`url(${require('@/assets/images/banner/banner.png')})`}"
			>
				<b-card-body class="text-center">
					<h2 class="text-primary">
						Not done yet
					</h2>
					<b-card-text class="mb-2">
						Please try again!!!
					</b-card-text>
				</b-card-body>
			</b-card>
		</b-col>
		<b-col
			v-if="interview.company"
			cols="12"
		>
			<b-card title="Review">
				<b-card-body v-if="userOn && userOn.candidate_id">
					<b-card-text>
						<div
							v-if="interview.result && interview.result.company"
							class="mail-message"
							v-html="interview.result.company.review"
						/>
						<div
							v-else
							class="mail-message"
						>No review</div>
					</b-card-text>
				</b-card-body>
				<b-card-body v-if="userOn && userOn.company_id" class="pt-0">
					<b-card-text>
						<validation-observer
							ref="reviewForm"
							v-slot="{invalid}"
						>
							<!-- form -->
							<b-form
								class="mt-2"
								@submit.prevent="saveReview"
							>
								<b-row>
									<b-col>
										<b-form-group
											label-for="news-edit-title"
											class="mb-2"
										>
											<validation-provider
												v-slot="{ errors }"
												name="Review"
												vid="review"
												rules="required"
											>
												<quill-editor
													id="review"
													v-model="review"
													name="review"
													:options="snowOption"
													aria-placeholder="reivew here"
													:state="errors.length > 0 ? false:null"
												/>
												<small class="text-danger">{{ errors[0] }}</small>
											</validation-provider>
										</b-form-group>
									</b-col>
									<b-col
										cols="12"
										class="mt-50 d-flex justify-content-between"
									>
										<b-button
											v-ripple.400="'rgba(255, 255, 255, 0.15)'"
											variant="primary"
											class="mr-1"
											type="submit"
											:disabled="invalid"
										>
											Save Review
										</b-button>

									</b-col>
								</b-row>
							</b-form>
						</validation-observer>
					</b-card-text>
				</b-card-body>
			</b-card>
		</b-col>
		<b-modal
			id="modal-detail-answer"
			cancel-variant="outline-secondary"
			ok-title="OK"
			cancel-title="Close"
			scrollable
			size="xl"
			title="Detail Answer"
		>
			<b-card
				tag="article"
				no-body
			>
				<b-card-body>
					<b-card-title>
						<div
							class="mail-message"
							v-html="question.title"
						/>
					</b-card-title>
					<div class="my-1 py-25">
						<b-link
							v-for="(tag,index) in question.tags"
							:key="index"
						>
							<b-badge
								pill
								class="mr-75"
								:variant="tagsColor(tag.name)"
							>
								{{ tag.name }}
							</b-badge>
						</b-link>
					</div>
					<b-media no-body>
						<b-media-body>
							<div
								class="mail-message"
								v-html="question.content"
							/>
						</b-media-body>
					</b-media>
					<b-card
						title="Your Answer"
						class="bg-light"
					>
						<b-card>
							<div
								v-if="question.answer"
								class="mail-message"
								v-html="question.answer"
							/>
							<span v-else> Not Answer</span>
						</b-card>
					</b-card>
					<hr>
					<b-card
						class="bg-light"
						title="Other Answer"
					>
						<b-card
							v-for="answer in question.answers"
							:key="answer.id"
						>
							<b-media no-body>
								<b-media-body>
									<b-card-title>
										<b-link class="text-body">Score: {{ answer.score }}</b-link>
										<span class="text-muted ml-75 mr-50">|</span>
										<feather-icon
											v-if="answer.answered"
											icon="CheckIcon"
											class="mr-50 text-success font-weight-bold"
											size="20"
										/>
									</b-card-title>

									<b-card-text>
										<div
											class="blog-content"
											v-html="answer.content"
										/>
									</b-card-text>
								</b-media-body>
							</b-media>
						</b-card>
					</b-card>
				</b-card-body>
			</b-card>
		</b-modal>
	</b-row>
	<b-row v-else>
		<b-col cols="12">
			<b-card
				no-body
				class="faq-search pt-5 pb-5"
				:style="{backgroundImage:`url(${require('@/assets/images/banner/banner.png')})`}"
			>
				<b-card-body class="text-center">
					<h2 class="text-primary">
						Nothing to show...
					</h2>
					<b-card-text class="mb-2">
						Please try again!!!
					</b-card-text>
				</b-card-body>
			</b-card>
		</b-col>
	</b-row>
	<!--/ question-test -->
</template>

<script>
	import {
		BRow,
		BCol,
		BCard,
		BCardText,
		BCardTitle,
		BMedia,
		BMediaBody,
		BCardBody,
		BLink,
		BPaginationNav,
		BBadge,
		VBModal,
    BForm,
    BFormGroup,
    BButton
	} from "bootstrap-vue";
	import { ValidationProvider, ValidationObserver } from "vee-validate";
	import { kFormatter } from "@core/utils/filter";
	import Ripple from "vue-ripple-directive";
	import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
	import { quillEditor } from "vue-quill-editor";
	import interview from "@/store/api/Interview";
  import { required, email, password } from '@validations'
	import utils from "@/store/utils";

	export default {
		components: {
			BRow,
			BCol,
			BCard,
			BCardText,
			BCardBody,
			BCardTitle,
			BMedia,
			BMediaBody,
			BLink,
			BPaginationNav,
			BBadge,
			quillEditor,
			ValidationProvider,
			ValidationObserver,
      BForm,
      BFormGroup,
      BButton,
		},
		directives: {
			"b-modal": VBModal,
			Ripple,
		},
		data() {
			return {
				id: null,
				interview: null,
				questions: null,
				question: {},
				result: null,
				currentPage: 1,
				perPage: 6,
				rows: 100,
				review: null,
				userOn: {},
				snowOption: {
					theme: "snow",
				},
			};
		},
		watch: {
			currentPage() {
				this.questions = this.interview.questions?.slice(
					(this.currentPage - 1) * this.perPage,
					this.currentPage * this.perPage
				);
			},
		},
		created() {
			const { id } = this.$route.params;
			if (id) {
				this.id = id - 0;
				this.getData();
			} else {
				this.id = null;
			}
		},
		methods: {
			kFormatter,
			tagsColor(tag) {
				console.log(tag);
				const color = [
					"primary",
					"secondary",
					"success",
					"danger",
					"warning",
					"info",
					"dark",
				];
				const rd = color[Math.floor(Math.random() * color.length)];
				return `light-${rd}`;
			},
			getData() {
				interview
					.findById(this.id)
					.then((resp) => {
						const rs = resp.data;
						this.interview = rs.data;
						this.currentPage = 1;
						this.result = this.interview?.result;
            this.review = this.result?.company?.review;
						this.rows =
							this.interview.questions?.length / this.perPage + 1;
						this.questions = this.interview.questions?.slice(
							(this.currentPage - 1) * this.perPage,
							this.currentPage * this.perPage
						);
						this.userOn = rs.user;
						utils.updateUser(rs.user);
						this.$ability.update([
							{
								action: "manage",
								subject: "all",
								// subject: userData.role,
							},
						]);
						this.userOn = rs.user;
					})
					.catch((err) => {
						console.log(err);
						this.interview = null;
					});
			},
			setQA(question) {
				console.log(question);
				this.question = question;
				this.question.answer =
					this.result?.candidate[`question-${question.id}`];
				this.$bvModal.show("modal-detail-answer");
			},
			saveReview() {
				this.$refs.reviewForm.validate().then((success) => {
					if (success) {
						interview
							.update(this.id, {
                candidate_id: this.interview.candidate.general.id,
								result: {
                  review: this.review,
                }
							})
							.then((resp) => {
								const rs = resp.data;
								this.interview = rs.data;
								this.userOn = rs.user;
								utils.updateUser(rs.user);
								this.$ability.update([
									{
										action: "manage",
										subject: "all",
										// subject: userData.role,
									},
								]);
								this.$toast({
									component: ToastificationContent,
									position: "top-right",
									props: {
										title: "Update success",
										icon: "CoffeeIcon",
										variant: "success",
									},
								});
								this.$router.push({ name: 'pages-news-edit', params: { idProject: this.interview.news.project_id, id: this.interview.news.id } })
							})
							.catch((err) => {
								console.log(err);
								this.$toast({
									component: ToastificationContent,
									position: "top-right",
									props: {
										title: "Error",
										icon: "AlertTriangleIcon",
										variant: "danger",
										text: "Something error or you are not company. Please try again!!!",
									},
								});
							});
					}
				});
			},
		},
	};
</script>

<style lang="scss" scoped>
@import "~@core/scss/vue/libs/quill.scss";
@import "~@core/scss/vue/pages/page-blog.scss";
</style>
