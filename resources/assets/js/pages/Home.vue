<template>
    <div class="container py-3">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h1>Compare different occupations</h1>
                <h4>You might be surprised how many of your talents crossover!</h4>
                <h6>There could be a job out there perfect for you that you have never thought about. Compare and see.</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <div class="form">
                            <div class="form-group row mb-2 text-center">
                                <div class="col-md-5">
                                    <label class="occupation-select-label">Occupation 1</label>
                                    <select-occupation :occupations="occupations" v-model="occupation_1"></select-occupation>
                                </div>
                                <div class="col-md-5">
                                    <label class="occupation-select-label">Occupation 2</label>
                                    <select-occupation :occupations="occupations" v-model="occupation_2"></select-occupation>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary btn-block mt-4" @click.prevent="compare" :disabled="!occupation_1 || !occupation_2 || !scopes.length || loading">
                                        <template v-if="loading">
                                            <i class="fa fa-refresh fa-spin"></i>
                                        </template>
                                        <template v-else>
                                            Compare
                                        </template>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-12">
                                    <label class="occupation-select-label">Scopes to include in the comparison</label>
                                    <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="checkbox" id="scopeCheck1" value="skills" v-model="scopes">
                                      <label class="form-check-label" for="scopeCheck1">Skills</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="checkbox" id="scopeCheck2" value="knowledge" v-model="scopes">
                                      <label class="form-check-label" for="scopeCheck2">Knowledge</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="checkbox" id="scopeCheck3" value="abilities" v-model="scopes">
                                      <label class="form-check-label" for="scopeCheck3">Abilities</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <template v-if="match && !loading">
                <div class="col-12 text-center">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <p class="h3"><strong>{{ occupation_1_label }}</strong></p>
                            <p class="h1">share a <strong>{{ match }}%</strong> similarity with</p>
                            <p class="h3"><strong>{{ occupation_2_label }}</strong></p>
                            <p class="h5">for <strong>{{ returned_scopes.join(", ") }}</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <ul class="nav nav-pills mb-3 flex-column flex-sm-row" id="myTab" role="tablist">
                        <li class="nav-item" v-if="returned_scopes.includes('skills')">
                            <a class="flex-sm-fill text-sm-center nav-link active" id="skills-tab" data-toggle="tab" href="#skills" role="tab" aria-controls="skills" aria-selected="true">Skills</a>
                        </li>
                        <li class="nav-item" v-if="returned_scopes.includes('knowledge')">
                            <a class="flex-sm-fill text-sm-center nav-link" id="knowledge-tab" data-toggle="tab" href="#knowledge" role="tab" aria-controls="knowledge" aria-selected="false">Knowledge</a>
                        </li>
                        <li class="nav-item" v-if="returned_scopes.includes('abilities')">
                            <a class="flex-sm-fill text-sm-center nav-link" id="abilities-tab" data-toggle="tab" href="#abilities" role="tab" aria-controls="abilities" aria-selected="false">Abilities</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="skills" role="tabpanel" aria-labelledby="skills-tab">
                            <div class="row">
                                <div class="col-6">
                                    <h3>Skills</h3>
                                </div>
                                <div class="col-6 text-right">
                                    <h3>{{ skills_similarity }}% similarity</h3>
                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Skill</th>
                                            <th scope="col">Importance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in skills" :key="item.label">
                                            <td>
                                                <h5>{{ item.label }}</h5>
                                                <p class="mb-0">{{ item.description }}</p>
                                            </td>
                                            <td class="text-center">{{ item.values[0] }} <i class="fa fa-arrow-right" aria-hidden="true"></i> {{ item.values[1] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="knowledge" role="tabpanel" aria-labelledby="knowledge-tab">
                            <div class="row">
                                <div class="col-6">
                                    <h3>Knowledge</h3>
                                </div>
                                <div class="col-6 text-right">
                                    <h3>{{ knowledge_similarity }}% similarity</h3>
                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Knowledge</th>
                                            <th scope="col">Importance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in knowledge" :key="item.label">
                                            <td>
                                                <h5>{{ item.label }}</h5>
                                                <p class="mb-0">{{ item.description }}</p>
                                            </td>
                                            <td class="text-center">{{ item.values[0] }} <i class="fa fa-arrow-right" aria-hidden="true"></i> {{ item.values[1] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="abilities" role="tabpanel" aria-labelledby="abilities-tab">
                            <div class="row">
                                <div class="col-6">
                                    <h3>Abilities</h3>
                                </div>
                                <div class="col-6 text-right">
                                    <h3>{{ abilities_similarity }}% similarity</h3>
                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Ability</th>
                                            <th scope="col">Importance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in abilities" :key="item.label">
                                            <td>
                                                <h5>{{ item.label }}</h5>
                                                <p class="mb-0">{{ item.description }}</p>
                                            </td>
                                            <td class="text-center">{{ item.values[0] }} <i class="fa fa-arrow-right" aria-hidden="true"></i> {{ item.values[1] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-else-if="!match && !loading && !error_message && !occupation_data_error">
                <div class="col-12 text-center">
                    Please select two Occupations and one or more Scopes from above and click Compare
                </div>
            </template>
            <template v-else-if="loading">
                <div class="col-12 text-center">
                    <i class="fa fa-refresh fa-spin fa-5x"></i>
                    <p class="mt-2">Please wait while we compare your selected occupations</p>
                </div>
            </template>
            <template v-else-if="!loading && error_message">
                <div class="col-12 text-center">
                    <p class="h2">Sorry it looks like something has gone wrong!</p>
                    <p>{{ error_message }}</p>
                </div>
            </template>
            <template v-else-if="!loading && !error_message && occupation_data_error">
                <div class="col-12 text-center">
                    <p class="h2">Oh no, sorry!</p>
                    <p>We weren't able to compare the selected occupations due to a lack of available data. Try changing one or both of the occupations</p>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import SelectOccupation from '../components/form-controls/SelectOccupation';
    export default {
        name: 'home-page',
        components: {
            SelectOccupation,
        },
        data() {
            return {
                loading: false,
                error_message: null,
                occupation_data_error: false,
                occupations: [],
                occupation_1: null,
                occupation_2: null,
                scopes: ['skills', 'knowledge', 'abilities'],
                returned_scopes: [],
                occupation_1_label: null,
                occupation_2_label: null,
                occupation_1_data: null,
                occupation_2_data: null,
                skills: null,
                skills_similarity: null,
                knowledge: null,
                knowledge_similarity: null,
                abilities: null,
                abilities_similarity: null,
                match: null
            }
        },
        methods: {
            compare() {
                this.loading = true;
                this.error_message = null;
                this.occupation_data_error = false;
                this.axios.post('/api/compare', {
                    occupation_1: this.occupation_1,
                    occupation_2: this.occupation_2,
                    scopes: this.scopes
                }).then((response) => {
                    this.loading = false;
                    this.match = response.data.match;
                    this.occupation_1_data = response.data.occupation_1;
                    this.occupation_2_data = response.data.occupation_2;
                    this.skills = response.data.skills;
                    this.skills_similarity = response.data.skills_similarity;
                    this.knowledge = response.data.knowledge;
                    this.knowledge_similarity = response.data.knowledge_similarity;
                    this.abilities = response.data.abilities;
                    this.abilities_similarity = response.data.abilities_similarity;
                    this.returned_scopes = response.data.scopes;
                    if(!this.returned_scopes.length){
                        this.occupation_data_error = true;
                    }
                }).catch((error) => {
                    this.loading = false;
                    if(error.response && error.response && error.response.statusText){
                        this.error_message = error.response.statusText;
                    }
                });

                var self = this;
                this.occupations.filter(function(elem){
                    if(elem.code == self.occupation_1){
                        self.occupation_1_label = elem.title;
                    }
                    if(elem.code == self.occupation_2){
                        self.occupation_2_label = elem.title;
                    }
                });
            }
        },
        async created() {
            let response = await this.axios.get('/api/occupations');
            this.occupations = response.data;
        }
    }
</script>

<style lang="scss" scoped>
    .form-group {
        label.occupation-select-label {
            font-size: 0.8rem;
            text-align: left;
            display: block;
            margin-bottom: 0.2rem
        }
    }
    .bg-light {
        background-color: #f0f0f0 !important;
    }
</style>