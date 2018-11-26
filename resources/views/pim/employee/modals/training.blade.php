<div id="add_new_training_modal" style="max-width:700px" class="popup-basic mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-title" v-if="singleTraining !=''">
                <i class="fa fa-rocket"></i>Edit Training
            </span>
             <span v-else class="panel-title">
                <i class="fa fa-rocket"></i>Add New Training
            </span>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <form id="add_new_training_form" method="post" v-on:submit.prevent="addNewTraining">
                        <input type="hidden" name="user_id" v-model="user_id">
                        <input type="hidden" v-if="singleTraining !=''" name="id" :value="(singleTraining.id)?singleTraining.id:''">

                        <div class="row">
                            <!-- <div class="col-md-3">
                                <div class="form-group" :class="{'has-error': errors.training_code}">
                                    <label class="control-label">Training Code : <span class="text-danger">*</span></label>
                                    <input type="text" name="training_code" class="form-control input-sm" :value="(singleTraining.training_code)?singleTraining.training_code:''">
                                    <span v-if="errors.training_code" class="text-danger">@{{ errors.training_code[0]}}</span>
                                </div>
                            </div> -->

                            <div class="col-md-6">
                                <div class="form-group" :class="{'has-error': errors.training_title}">
                                    <label class="control-label">Training Title  : <span class="text-danger">*</span></label>

                                    <input type="text" name="training_title" class="form-control input-sm" v-model="singleTraining.training_title">
                                    
                                    <span v-if="errors.training_title" class="text-danger" v-text="errors.training_title[0]"></span>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group" :class="{'has-error': errors.training_institute}">
                                    <label class="control-label">Training Institute : <span class="text-danger">*</span></label>
                                    
                                    <input type="text" name="training_institute" class="form-control input-sm" v-model="singleTraining.training_institute">
                                    
                                    <span v-if="errors.training_institute" class="text-danger" v-text="errors.training_institute[0]"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-error': errors.training_from_date}">
                                    <label class="control-label">From Date : <span class="text-danger">*</span></label>
                                    
                                    <input type="text" name="training_from_date" v-on:mouseover="myDatePicker" class="mydatepicker form-control input-sm" readonly="readonly" v-model="singleTraining.training_from_date">
                                    
                                    <span v-if="errors.training_from_date" class="text-danger" v-text="errors.training_from_date[0]"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-error': errors.training_to_date}">
                                    <label class="control-label">To Date : <span class="text-danger">*</span></label>
                                    
                                    <input type="text" name="training_to_date" v-on:mouseover="myDatePicker" class="mydatepicker form-control input-sm" readonly="readonly" v-model="singleTraining.training_to_date">
                                    
                                    <span v-if="errors.training_to_date" class="text-danger" v-text="errors.training_to_date[0]"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-error': errors.training_passed_date}">
                                    <label class="control-label">Passed Date : <span class="text-danger">*</span></label>
                                    
                                    <input type="text" name="training_passed_date" v-on:mouseover="myDatePicker" class="mydatepicker form-control input-sm" readonly="readonly" v-model="singleTraining.training_passed_date">
                                    
                                    <span v-if="errors.training_passed_date" class="text-danger" v-text="errors.training_passed_date[0]"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-error': errors.training_participation_date}">
                                    <label class="control-label">Participation Date : <span class="text-danger">*</span></label>
                                    
                                    <input type="text" name="training_participation_date" v-on:mouseover="myDatePicker" class="mydatepicker form-control input-sm" readonly="readonly" v-model="singleTraining.training_participation_date">
                                    
                                    <span v-if="errors.training_participation_date" class="text-danger" v-text="errors.training_participation_date[0]"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" :class="{'has-error': errors.training_remarks}">
                                    <label class="control-label">Training remarks : <span class="text-danger">*</span></label>
                                    
                                    <textarea type="text" name="training_remarks" class="form-control input-sm" v-model="singleTraining.training_remarks"></textarea>
                                    
                                    <span v-if="errors.training_remarks" class="text-danger" v-text="errors.training_remarks[0]"></span>
                                </div>
                            </div>
                        </div>

                        <hr class="short alt">

                        <div class="section row mbn">
                            <div class="col-sm-4 pull-right">
                                <p class="text-left">
                                    <button v-if="singleTraining !=''" type="submit" name="save_training" class="btn btn-dark btn-gradient dark btn-block"><span class="glyphicons glyphicons-ok_2"></span> &nbsp; Update Training
                                    </button>
                                     <button v-else type="submit" name="save_training" class="btn btn-dark btn-gradient dark btn-block"><span class="glyphicons glyphicons-ok_2"></span> &nbsp; Add New
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