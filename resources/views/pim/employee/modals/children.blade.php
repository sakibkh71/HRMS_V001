<div id="add_new_children_modal" style="max-width:700px" class="popup-basic mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-title" v-if="singleChildren !=''">
                <i class="fa fa-rocket"></i>Edit Children
            </span>
            <span v-else class="panel-title">
                <i class="fa fa-rocket"></i>Add New Children
            </span>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <form id="add_new_children_form" method="post" v-on:submit.prevent="addNewChildren">
                        <input type="hidden" name="user_id" v-model="user_id">
                        <input type="hidden" name="id" v-if="singleChildren !=''" v-model="singleChildren.id">

                         <div class="row">
                            <div class="col-md-4">
                                <div class="form-group" :class="{'has-error': errors.children_name}">
                                    <label class="control-label">Children Name : <span class="text-danger">*</span></label>
                                    <input type="text" name="children_name" class="form-control input-sm" v-model="singleChildren.children_name">
                                    <span v-if="errors.children_name" class="text-danger">@{{ errors.children_name[0]}}</span>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group" :class="{'has-error': errors.children_education_level}">
                                    <label class="control-label">Children Education Level : <span class="text-danger">*</span></label>
                                    <input type="text" name="children_education_level" class="form-control input-sm" v-model="singleChildren.children_education_level">
                                    <span v-if="errors.children_education_level" class="text-danger">@{{ errors.children_education_level[0]}}</span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-error': errors.children_birth_date}">
                                    <label class="control-label">Children Birth Date  : <span class="text-danger">*</span></label>
                                    <input type="text" name="children_birth_date" v-on:mouseover="myDatePicker" class="mydatepicker form-control input-sm" readonly="readonly" v-model="singleChildren.children_birth_date">
                                    <span v-if="errors.children_birth_date" class="text-danger">@{{ errors.children_birth_date[0]}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group" :class="{'has-error': errors.children_gender}">
                                    <label class="control-label">Children Gender : <span class="text-danger">*</span></label>
                                    <div class="radio-custom mb5">
                                        <input id="children_male" name="children_gender" type="radio" value="male" v-model="singleChildren.children_gender">
                                        <label for="children_male">Male</label>
                                        <input id="children_female" name="children_gender" type="radio" value="female" v-model="singleChildren.children_gender">
                                        <label for="children_female">Female</label>
                                    </div>
                                    <span v-if="errors.children_gender" class="text-danger">@{{ errors.children_gender[0]}}</span>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group" :class="{'has-error': errors.children_remarks}">
                                    <label class="control-label">Children Remarks : <span class="text-danger">*</span></label>
                                    <textarea name="children_remarks" class="form-control input-sm" v-model="singleChildren.children_remarks"></textarea>
                                    <span v-if="errors.children_remarks" class="text-danger">@{{ errors.children_remarks[0]}}</span>
                                </div>
                            </div>
                        </div>

                        <hr class="short alt">

                        <div class="section row mbn">
                            <div class="col-sm-4 pull-right">
                                <p class="text-left">
                                    <button type="submit" v-if="singleChildren !=''" name="save_children" class="btn btn-dark btn-gradient dark btn-block"><span class="glyphicons glyphicons-ok_2"></span> &nbsp; Update Children
                                    </button>
                                    <button v-else type="submit" name="save_children" class="btn btn-dark btn-gradient dark btn-block"><span class="glyphicons glyphicons-ok_2"></span> &nbsp; Add New
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