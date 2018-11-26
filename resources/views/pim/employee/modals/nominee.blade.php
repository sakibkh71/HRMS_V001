<div id="add_new_nominee_modal" style="max-width:780px" class="popup-basic mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-title" v-if="singleNominee !=''">
                <i class="fa fa-rocket"></i>Edit Nominee
            </span>
            <span v-else class="panel-title">
                <i class="fa fa-rocket"></i>Add New Nominee
            </span>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <form id="add_new_nominee_form" method="post" v-on:submit.prevent="addNomineeInfo">
                        <input type="hidden" name="user_id" v-model="user_id">
                        <input type="hidden" name="id" v-if="singleNominee !=''" v-model="singleNominee.id">

                        <div v-if="singleNominee !=''">
                            <div class="col-md-2" :class="{'has-error': errors.image}">
                                <label class="control-label">Photo :</label>

                                <div class="fileupload-new admin-form" data-provides="fileupload">
                                    <div class="fileupload-preview thumbnail mb5">
                                         <img v-if="singleNominee.nominee_photo" :src="'/files/'+config_id+'/'+singleNominee.user_id+'/'+singleNominee.nominee_photo">
                                        <img v-else src="{{asset('img/placeholder.png')}}" alt="holder">
                                    </div>
                                    <span class="button btn btn-sm btn-dark btn-file btn-block ph5">
                                        <span class="fileupload-exists"><span class="fa fa-user"></span> &nbsp; <strong>Change</strong></span>
                                        <span class="fileupload-new"><span class="fa fa-user"></span> &nbsp; <strong>Select</strong></span>
                                        <input type="file" name="image">
                                    </span>
                                </div>
                                <span v-if="errors.image" class="help-block" v-text="errors.image[0]"></span>
                            </div>

                            <div class="col-md-10 mt40">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group" :class="{'has-error': errors.nominee_name}">
                                            <label class="control-label">Nominee Name : <span class="text-danger">*</span></label>
                                            <input type="text" name="nominee_name" :value="singleNominee.nominee_name" class="form-control input-sm">
                                            <span v-if="errors.nominee_name" class="help-block" v-text="errors.nominee_name[0]"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group" :class="{'has-error': errors.nominee_relation}">
                                            <label class="control-label">Nominee Relation : <span class="text-danger">*</span></label>
                                            <input type="text" name="nominee_relation" class="form-control input-sm" :value="singleNominee.nominee_relation">
                                             <span v-if="errors.nominee_relation" class="help-block" v-text="errors.nominee_relation[0]"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group" :class="{'has-error': errors.nominee_birth_date}">
                                            <label class="control-label">Nominee Birth Date : <span class="text-danger">*</span></label>
                                            <input type="text" name="nominee_birth_date" v-on:mouseover="myDatePicker" class="mydatepicker form-control input-sm" readonly="readonly" :value="singleNominee.nominee_birth_date">
                                            <span v-if="errors.nominee_birth_date" class="help-block" v-text="errors.nominee_birth_date[0]"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group" :class="{'has-error': errors.nominee_distribution}">
                                            <label class="control-label">Nominee Distribution : <span class="text-danger">*</span></label>
                                            <input type="text" name="nominee_distribution" v-on:keyup="nomineeDistributionEdit($event.target.value)" class="form-control input-sm" v-model="singleNominee.nominee_distribution">
                                            <span v-if="errors.nominee_distribution" class="help-block" v-text="errors.nominee_distribution[0]"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group" :class="{'has-error': errors.nominee_rest_distribution}">
                                            <label class="control-label">Nominee Rest Distribution : <span class="text-danger">*</span></label>
                                            <input type="text" name="nominee_rest_distribution" class="form-control input-sm" :value="singleNominee.nominee_rest_distribution" readonly="readonly">
                                            <span v-if="errors.nominee_rest_distribution" class="help-block" v-text="errors.nominee_rest_distribution[0]"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group" :class="{'has-error': errors.nominee_address}">
                                            <label class="control-label">Nominee Address : <span class="text-danger">*</span></label>
                                            <input type="text" name="nominee_address" class="form-control input-sm" :value="singleNominee.nominee_address">
                                            <span v-if="errors.nominee_address" class="help-block" v-text="errors.nominee_address[0]"></span>
                                        </div>
                                    </div>
                                </div>

                                <hr class="short alt">
                            </div>
                        </div>

                        <div v-else>
                            <div class="col-md-2" :class="{'has-error': errors.image}">
                                <label class="control-label">Photo :</label>
                                <div class="fileupload-new admin-form" data-provides="fileupload">
                                    <div class="fileupload-preview thumbnail mb5">
                                        <img src="{{asset('img/placeholder.png')}}" alt="holder">
                                    </div>
                                    <span class="button btn btn-sm btn-dark btn-file btn-block ph5">
                                    <span class="fileupload-exists"><span class="fa fa-user"></span> &nbsp; <strong>Change</strong></span>
                                    <span class="fileupload-new"><span class="fa fa-user"></span> &nbsp; <strong>Select</strong></span>
                                    <input type="file" name="image">
                                </span>
                                </div>
                                <span v-if="errors.image" class="help-block" v-text="errors.image[0]"></span>
                            </div>

                            <div class="col-md-10 mt40">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group" :class="{'has-error': errors.nominee_name}">
                                            <label class="control-label">Nominee Name : <span class="text-danger">*</span></label>
                                            <input type="text" name="nominee_name" class="form-control input-sm">
                                            <span v-if="errors.nominee_name" class="help-block" v-text="errors.nominee_name[0]"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group" :class="{'has-error': errors.nominee_relation}">
                                            <label class="control-label">Nominee Relation : <span class="text-danger">*</span></label>
                                            <input type="text" name="nominee_relation" class="form-control input-sm">
                                            <span v-if="errors.nominee_relation" class="help-block" v-text="errors.nominee_relation[0]"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group" :class="{'has-error': errors.nominee_birth_date}">
                                            <label class="control-label">Nominee Birth Date : <span class="text-danger">*</span></label>
                                            <input type="text" name="nominee_birth_date" v-on:mouseover="myDatePicker" class="mydatepicker form-control input-sm" readonly="readonly">
                                            <span v-if="errors.nominee_birth_date" class="help-block" v-text="errors.nominee_birth_date[0]"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group" :class="{'has-error': errors.nominee_distribution}">
                                            <label class="control-label">Nominee Distribution : <span class="text-danger">*</span></label>
                                            <input type="text" name="nominee_distribution" v-on:keyup="nomineeDistribution" v-model="nominee_distribution" class="form-control input-sm">
                                            <span v-if="errors.nominee_distribution" class="help-block" v-text="errors.nominee_distribution[0]"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group" :class="{'has-error': errors.nominee_rest_distribution}">
                                            <label class="control-label">Nominee Rest Distribution : <span class="text-danger">*</span></label>
                                            <input type="text" name="nominee_rest_distribution" :value="nominee_rest_distribution" class="form-control input-sm" readonly="readonly">
                                            <span v-if="errors.nominee_rest_distribution" class="help-block" v-text="errors.nominee_rest_distribution[0]"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group" :class="{'has-error': errors.nominee_address}">
                                            <label class="control-label">Nominee Address : <span class="text-danger">*</span></label>
                                            <input type="text" name="nominee_address" class="form-control input-sm">
                                            <span v-if="errors.nominee_address" class="help-block" v-text="errors.nominee_address[0]"></span>
                                        </div>
                                    </div>
                                </div>
                                <hr class="short alt">
                            </div>
                        </div>

                        <hr class="short alt">

                        <div class="section row mbn">
                            <div class="col-sm-4 pull-right">
                                <p class="text-left">
                                    <button type="submit" v-if="singleNominee !=''" name="save_nominee" class="btn btn-dark btn-gradient dark btn-block"><span class="glyphicons glyphicons-ok_2"></span> &nbsp; Update Nominee
                                    </button>
                                    <button v-else type="submit" name="save_nominee" class="btn btn-dark btn-gradient dark btn-block"><span class="glyphicons glyphicons-ok_2"></span> &nbsp; Add New
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