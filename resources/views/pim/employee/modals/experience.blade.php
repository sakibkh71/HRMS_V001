
<div id="add_new_experience_modal" style="max-width:700px" class="popup-basic mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
           <span class="panel-title" v-if="singleExperience,singleExperience !=''">
                <i class="fa fa-rocket"></i>Edit Experience
            </span>
            <span v-else class="panel-title">
                <i class="fa fa-rocket"></i>Add New Experience
            </span>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <form id="add_new_experience_form" method="post" v-on:submit.prevent="addNewExperience">
                        <input type="hidden" name="user_id" v-model="user_id">
                        
                        <div v-if="singleExperience,singleExperience !=''">
                            <input type="hidden" name="id" :value="singleExperience.id">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" :class="{'has-error': errors.company_name}">
                                        <label class="control-label">Company Name : <span class="text-danger">*</span></label>
                                        <input type="text" name="company_name" :value="singleExperience.company_name" class="form-control input-sm">
                                        <span v-if="errors.company_name" class="text-danger" v-text="errors.company_name[0]"></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group" :class="{'has-error': errors.position_held}">
                                        <label class="control-label">Position Held : <span class="text-danger">*</span></label>
                                        <input type="text" name="position_held" :value="singleExperience.position_held" class="form-control input-sm">
                                        <span v-if="errors.position_held" class="text-danger" v-text="errors.position_held[0]"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group" :class="{'has-error': errors.job_start_date}">
                                        <label class="control-label">Job Start Date : <span class="text-danger">*</span></label>
                                        <input type="text" id="job_start_date3" name="job_start_date" :value="singleExperience.job_start_date" v-on:mouseover="myDatePicker" class="mydatepicker form-control input-sm" readonly="readonly">
                                        <span v-if="errors.job_start_date" class="text-danger" v-text="errors.job_start_date[0]"></span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group" :class="{'has-error': errors.job_end_date}">
                                        <label class="control-label">Job End Date : <span class="text-danger">*</span></label>
                                        <input type="text" id="job_end_date3" name="job_end_date" :value="singleExperience.job_end_date" v-on:mouseover="myDatePicker" class="mydatepicker form-control input-sm" readonly="readonly">
                                        <span v-if="errors.job_end_date" class="text-danger" v-text="errors.job_end_date[0]"></span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group" :class="{'has-error': errors.job_duration}">
                                        <label class="control-label">Job Duration : <span class="text-danger">*</span></label>
                                        <input type="text" name="job_duration" v-on:click="theDuration3" :value="singleExperience.job_duration" class="form-control input-sm">
                                        <span v-if="errors.job_duration" class="text-danger" v-text="errors.job_duration[0]"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" :class="{'has-error': errors.job_responsibility}">
                                        <label class="control-label">Job Responsibility : <span class="text-danger">*</span></label>
                                        <textarea type="text" name="job_responsibility" v-text="singleExperience.job_responsibility" class="form-control input-sm"></textarea>
                                        <span v-if="errors.job_responsibility" class="text-danger" v-text="errors.job_responsibility[0]"></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group" :class="{'has-error': errors.job_location}">
                                        <label class="control-label">Job Location : <span class="text-danger">*</span></label>
                                        <textarea type="text" name="job_location" class="form-control input-sm" v-text="singleExperience.job_location"></textarea>
                                        <span v-if="errors.job_location" class="text-danger" v-text="errors.job_location[0]"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-else>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" :class="{'has-error': errors.company_name}">
                                        <label class="control-label">Company Name : <span class="text-danger">*</span></label>
                                        <input type="text" name="company_name" class="form-control input-sm">
                                        <span v-if="errors.company_name" class="text-danger" v-text="errors.company_name[0]"></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group" :class="{'has-error': errors.position_held}">
                                        <label class="control-label">Position Held : <span class="text-danger">*</span></label>
                                        <input type="text" name="position_held" class="form-control input-sm">
                                        <span v-if="errors.position_held" class="text-danger" v-text="errors.position_held[0]"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group" :class="{'has-error': errors.job_start_date}">
                                        <label class="control-label">Job Start Date : <span class="text-danger">*</span></label>
                                        <input type="text" id="job_start_date2" v-on:mouseover="myDatePicker" name="job_start_date" class="mydatepicker form-control input-sm" readonly="readonly">
                                        <span v-if="errors.job_start_date" class="text-danger" v-text="errors.job_start_date[0]"></span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group" :class="{'has-error': errors.job_end_date}">
                                        <label class="control-label">Job End Date : <span class="text-danger">*</span></label>
                                        <input type="text" id="job_end_date2" v-on:mouseover="myDatePicker" name="job_end_date" class="mydatepicker form-control input-sm" readonly="readonly">
                                        <span v-if="errors.job_end_date" class="text-danger" v-text="errors.job_end_date[0]"></span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group" :class="{'has-error': errors.job_duration}">
                                        <label class="control-label">Job Duration : <span class="text-danger">*</span></label>
                                        <input type="text" name="job_duration" v-on:click="theDuration2" v-model="job_duration" class="form-control input-sm">
                                        <span v-if="errors.job_duration" class="text-danger" v-text="errors.job_duration[0]"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" :class="{'has-error': errors.job_responsibility}">
                                        <label class="control-label">Job Responsibility : <span class="text-danger">*</span></label>
                                        <textarea type="text" name="job_responsibility" class="form-control input-sm"></textarea>
                                        <span v-if="errors.job_responsibility" class="text-danger">@{{ errors.job_responsibility[0]}}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group" :class="{'has-error': errors.job_location}">
                                        <label class="control-label">Job Location : <span class="text-danger">*</span></label>
                                        <textarea type="text" name="job_location" class="form-control input-sm"></textarea>
                                        <span v-if="errors.job_location" class="text-danger" v-text="errors.job_responsibility[0]"></span>
                                    </div>
                                </div>
                            </div>
                        </div>    

                        <hr class="short alt">

                        <div class="section row mbn">
                            <div class="col-sm-4 pull-right">
                                <p class="text-left">
                                    <button v-if="singleExperience,singleExperience !=''" type="submit" name="save_experience" class="btn btn-dark btn-gradient dark btn-block"><span class="glyphicons glyphicons-ok_2"></span> &nbsp; Update Experience
                                    </button>

                                    <button v-else type="submit" name="save_experience" class="btn btn-dark btn-gradient dark btn-block"><span class="glyphicons glyphicons-ok_2"></span> &nbsp; Add New
                                    </button>
                                </p>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>