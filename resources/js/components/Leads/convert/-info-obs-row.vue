<template>
    <div class="mb-4 info-obs">
        <div class="row mb-4" v-if="is_not_qualified">
            <div class="col-12">
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">{{ lead.f_status }}</h4>
                    <p>
                        {{ lead.objection }}
                        {{ lead.other_objection }}
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7 col-sm-12">
                <el-tabs type="border-card">
                    <el-tab-pane label="Contatos">
                        <el-timeline class="pt-3">
							<el-timeline-item>
                                <p class="f-12 text-muted">
                                    <a href="#" @click.prevent="addContact" class="link" style="font-size: 14px;">
										<span class="el-icon-circle-plus mr-1" />Novo Contato
									</a>
                                </p>
                            </el-timeline-item>
							<template v-if="lead.tries.length > 0">
								<el-timeline-item  v-for="(t, i) in lead.tries" :key="i" :timestamp="`${t.date} - ${t.timestamp}`">
									<p class="f-12 text-muted">
										<p class="mr-1 mb-0 f-12 " v-if="t.type">Tipo : <b v-html="t.type" /></p>
										<p class="mr-1 mb-0 f-12" v-if="t.user">Por : <b v-html="t.user" /></p>
										<p class="mr-1 mb-0 f-12" v-if="t.obs">Observações : <b v-html="t.obs" /></p>
										<p class="mr-1 mb-0 f-12" v-if="t.comment">Comentário : <b v-html="t.comment" /></p>
										<p class="mr-1 mb-0 f-12" v-if="t.objection">
											Objeção : <b v-html="t.objection" /><span class="ml-1" v-if="t.other_objection" v-html="`( ${t.other_objection} )`"/>
										</p>
									</p>
								</el-timeline-item>
							</template>
							<div class="text-muted text-center f-12" v-else>Nenhuma contato anterior</div>
                        </el-timeline>
                    </el-tab-pane>
                    <el-tab-pane label="Conversões Anteriores">
                        <el-timeline class="pt-3" v-if="lead.conversions.length > 0">
                            <el-timeline-item v-for="(conversion, i) in lead.conversions" :key="i" :timestamp="`${conversion.date} - ${conversion.timestamp}`">
                                <p class="mb-0 f-12" v-html="conversion.desc" />
                                <p class="mb-0  f-12" v-html="conversion.obs" />
                                <p class="f-12 text-muted" v-if="conversion.user">
                                    <span class="mr-1">Por : <b v-html="conversion.user" /></span>
                                </p>
                            </el-timeline-item>
                        </el-timeline>
                        <div class="text-muted text-center f-12" v-else>Nenhuma conversão anterior</div>
                    </el-tab-pane>
					<el-tab-pane label="Lead Completo (Webhook)" v-if="lead.lead_api">
						 <pre class="f-12">{{ lead.lead_api || pretty }}</pre>
                    </el-tab-pane>
                </el-tabs>
            </div>
            <div class="col-md-5 col-sm-12 bg-light d-flex flex-column py-3">
                <div class="d-flex flex-row align-items-center mb-3 f-12">
                    <b class="mr-1">Observações :</b>
                    <span v-html="lead.obs" />
                </div>
                <div class="d-flex flex-row align-items-center f-12">
                    <b class="mr-1">Comentários :</b>
                    <span v-html="lead.comment" />
                </div>
				
				<el-dialog title="Novo Contato / Atendimento" :visible.sync="form_new_contact.visible" width="85%" :close-on-click-modal="false" top="10">
                    <el-steps :active="form_new_contact.step" align-center finish-status="success" :space="500">
						<el-step title="Step 1" description="Defina o tipo do contato"/>
						<el-step title="Step 2" description="Resposta do contato"/>
						<el-step title="Step 3" description="Objeção do contato"/>
						<el-step title="Step 4" description="Dados para agendamento"/>
						<el-step title="Step 5" description="Conclusão do Contato"/>
					</el-steps>
					<div class="d-flex flex-column my-4 mt-5">
						<template v-if="form_new_contact.step == 0">
							<label>Selecione o tipo de contato</label>
							<el-select
								clearable
								v-model="form_new_contact.type_id"
								filterable
								placeholder="Selecione o tipo de contato"
							>
								<el-option v-for="(type,i) in contact_types" :key="i" :label="type.description" :value="type.id" />
							</el-select>
							<small class="text-muted">Selecione o meio que o operador utilizará para efetuar o contato com o lead</small>
							<hr>
							<span slot="footer" class="dialog-footer d-flex justify-content-end">
								<el-button type="primary" @click="finishStepOne">
									Avançar<span class="el-icon-right ml-2" />
								</el-button>
							</span>
						</template>
						<template v-if="form_new_contact.step == 1">
							<label>Informe a resposta do contato</label>
							<el-select v-model="form_new_contact.answer_id" placeholder="Informe a resposta do contato">
								<el-option-group v-for="(group, g) in grouped_answer_types" :key="g" :label="group.type">
									<el-option v-for="(answer, x) in group.options" :key="x"  :label="answer.description" :value="answer.id" />
								</el-option-group>
							</el-select>
							<small class="text-muted">Selecione a resposta do contato referente a proposta realizada</small>
							<hr>
							<span slot="footer" class="dialog-footer d-flex justify-content-end">
								<el-button @click="form_new_contact.step = 0">
									<span class="el-icon-back mr-2" />Voltar
								</el-button>
								<el-button type="primary" @click="finishStepTwo">
									Avançar<span class="el-icon-right ml-2" />
								</el-button>
							</span>
						</template>
						<template v-if="form_new_contact.step == 2">
							<label>Selecione a objeção informada pelo contato</label>
							<el-select v-model="form_new_contact.objection_id" placeholder="Selecione a objeção do contato">
								<el-option v-for="(ob,i) in objection_options" :key="i"  :label="ob.description" :value="ob.id" />
							</el-select>
							<small class="text-muted">Selecione a resposta do contato referente a proposta realizada</small>
							<div class="mt-3 d-flex flex-column">
								<label>Detalhes</label>
								<textarea
									v-model="form_new_contact.other_objection" 
									class="form-control"
									:rows="3"
									style="resize:none;"
								/>
								<small class="text-muted">Dependendo da objeção escolhida, o detalhamento é obrigatório</small>
							</div>
							<hr>
							<span slot="footer" class="dialog-footer d-flex justify-content-end">
								<el-button @click="form_new_contact.step = 1">
									<span class="el-icon-back mr-2" />Voltar
								</el-button>
								<el-button type="primary" @click="finishStepThree">
									Avançar<span class="el-icon-right ml-2" />
								</el-button>
							</span>
						</template>
						<template v-if="form_new_contact.step == 3">
							<label v-if="selected_answer.behavior == 'need_schedule_test'">Selecione informações para agendamento do vestibular</label>
							<label v-else>Selecione informações para reagendamento de contato</label>
							<el-date-picker
								v-model="form_new_contact.schedule"
								type="datetime"
								placeholder="Seleciona a data e hora"
								class="w-100"
								format="dd/MM/yyyy  HH:mm:ss"
							/>
							<small class="text-muted" v-if="selected_answer.behavior == 'need_schedule_test'">Nesta data e hora o lead deverá comparecer para realizar a prova</small>
							<small class="text-muted" v-else>Nesta data e hora seu usuário será notificado para efetuar um novo contato com este lead</small>
							<hr>
							<span slot="footer" class="dialog-footer d-flex justify-content-end">
								<el-button @click="form_new_contact.step = 1">
									<span class="el-icon-back mr-2" />Voltar
								</el-button>
								<el-button type="primary" @click="finishStepFour">
									Avançar<span class="el-icon-right ml-2" />
								</el-button>
							</span>
						</template>
						<template v-if="form_new_contact.step == 4">
							<div class="jumbotron">
								<div class="row">
									<div class="col-md-8 col-sm-12">
										<h4  class="mb-4"><b>Resumo do Contato</b></h4>
										<p class="mb-0"><b>Tipo de Contato : </b>{{ selected_contact_type.description }}</p>
										<p class="mb-0" v-if="form_new_contact.schedule"><b>Agendamento : </b>{{ formatDate(form_new_contact.schedule) }}</p>
										<p class="mb-0"><b>Tipo de Resposta : </b>{{ selected_answer.f_type }}</p>
										<p class="mb-0"><b>Resposta do Contato : </b>{{ selected_answer.description }}</p>
										<p class="mb-0" v-if="selected_objection"><b>Objeção : </b>{{ selected_objection.description }}</p>
										<p class="mb-0" v-if="form_new_contact.other_objection"><b>Descrição da Objeção : </b>{{form_new_contact.other_objection}}</p>
									</div>
									<div class="col-md-4 col-sm-12">
										<label>Observações<small class="text-muted ml-2">Informações adicionais do contato</small></label>
										<textarea
											v-model="form_new_contact.obs" 
											class="form-control"
											:rows="4"
											style="resize:none;"
										/>
										<small class="text-muted">Digite aqui caso queira adicionar alguma observação sobre este contato</small>
									</div>
								</div>
							</div>
							<hr>
							<span slot="footer" class="dialog-footer d-flex justify-content-end">
								<el-button @click="cancelStepFour">
									<span class="el-icon-back mr-2" />Voltar
								</el-button>
								<el-button type="primary" @click="confirmContact">
									Concluir<span class="el-icon-check ml-2" />
								</el-button>
							</span>
						</template>
					</div>
                </el-dialog>
            </div>
        </div>
    </div>
</template>
<script>
const new_contact = () => {
    return {
        step: 0,
        visible: false,
        type_id: null,
        answer_id: null,
        objection_id: null,
        other_objection: null,
        schedule: null,
        obs: null,
    }
}
export default {
    data() {
        return {
            editing_form: {
                field: null,
                title: null,
                value: null,
            },
            form_new_contact: new_contact(),
        }
    },
    computed: {
        selected_objection() {
            if (!this.form_new_contact.objection_id) return
            return this.objection_options.find((x) => x.id == this.form_new_contact.objection_id)
        },
        selected_answer() {
            if (!this.form_new_contact.answer_id) return
            return this.answers.find((x) => x.id == this.form_new_contact.answer_id)
        },
        selected_contact_type() {
            if (!this.form_new_contact.type_id) return
            return this.contact_types.find((x) => x.id == this.form_new_contact.type_id)
        },
        grouped_answer_types() {
            return _(this.answers)
                .groupBy('f_type')
                .map((options, type) => ({ options, type }))
                .value()
        },
        answers() {
            return this.$store.state.answers
        },
        objection_options() {
            return this.$store.state.objections
        },
        contact_types() {
            return this.$store.state.types
        },
        lead() {
            return this.$store.state.lead
        },
        is_not_qualified() {
            return this.lead.status.value == 'canceled'
        },
    },
    methods: {
        confirmContact() {
            let loading = this.$loading({ text: 'Finalizando contato ...' })
            this.$http
                .post(`${window.location.pathname}/convertion`, { ...this.form_new_contact, back_query: window.location.search })
                .then(({ data }) => {
                    if (data.success && data.route) {
                        window.location.href = data.route
                    }
                })
                .catch((er) => {
                    console.log(er)
                    loading.close()
                })
        },
        clearScheduleAndObjectionsAndGoTo(step) {
            this.form_new_contact.objection_id = null
            this.form_new_contact.other_objection = null
            this.form_new_contact.schedule = null
            this.form_new_contact.step = step
        },
        cancelStepFour() {
            let answer = this.selected_answer
            if (answer.behavior == 'need_objection') return this.clearScheduleAndObjectionsAndGoTo(2)
            if (['need_schedule', 'need_schedule_test'].includes(answer.behavior)) return this.clearScheduleAndObjectionsAndGoTo(3)
            this.form_new_contact.step = 1
        },
        formatDate(date) {
            return this.$moment(date).format('DD/mm/YYYY - HH:mm:ss')
        },
        finishStepFour() {
            if (!this.form_new_contact.schedule) return this.$message.error('Define a data e hora do agendamento')
            this.form_new_contact.step = 4
        },
        finishStepThree() {
            if (!this.form_new_contact.objection_id) return this.$message.error('Selecione a objeção do contato')
            let objection = this.selected_objection
            if (objection.need_description) {
                if (!this.form_new_contact.other_objection) return this.$message.error('Informe a descrição da objeção do contato')
            }
            this.form_new_contact.step = 4
        },
        finishStepTwo() {
            let answer = this.selected_answer
            if (answer.behavior == 'need_objection') return this.clearScheduleAndObjectionsAndGoTo(2)
            if (['need_schedule', 'need_schedule_test'].includes(answer.behavior)) return this.clearScheduleAndObjectionsAndGoTo(3)
            this.form_new_contact.step = 4
        },
        finishStepOne() {
            if (!this.form_new_contact.type_id) return this.$message.error('Selecione o tipo do contato')
            this.form_new_contact.step++
        },
        confirmNewContact() {
            this.form_new_contact.visible = false
        },
        addContact() {
            this.form_new_contact = new_contact()
            this.form_new_contact.visible = true
        },
    },
}
</script>
<style lang="scss">
.info-obs {
    .el-tabs__content {
        height: 300px;
        min-height: 300px;
        max-height: 300px;
        overflow-y: auto;
    }
}
</style>