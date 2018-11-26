<div id="add_new_designation_modal" style="max-width:700px" class="popup-basic mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-title">
                <i class="fa fa-rocket"></i>Add New Designation
            </span>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" v-on:submit.prevent="addNewDesignation('add_new_designation_form')"
                          id="add_new_designation_form">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group" :class="{'has-error': errors.designation_name}">
                                    <label class="control-label">Designation Name : <span class="text-danger">*</span></label>
                                    <input type="text" name="designation_name" class="form-control input-sm">
                                    <span v-if="errors.designation_name" class="text-danger">@{{ errors.designation_name[0]}}</span>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group" :class="{'has-error': errors.level_id}">
                                    <label class="control-label">Level : <span class="text-danger">*</span></label>
                                    <select class="form-control input-sm" name="level_id">
                                        <option v-bind:value="''">---- Select Level ----</option>
                                        <option v-bind:value="level.id" v-for="(level, index) in levels">@{{ level.level_name }}</option>
                                    </select>
                                    <span v-if="errors.level_id" class="text-danger">@{{ errors.level_id[0]}}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group" :class="{'has-error': errors.department_id}">
                                    <label class="control-label">Department : <span class="text-danger">*</span></label>
                                    <select class="form-control input-sm" name="department_id">
                                        <option v-bind:value="''">---- Select Department ----</option>
                                        <option v-bind:value="department.id" v-for="(department, index) in departments">@{{ department.department_name }}</option>
                                    </select>
                                    <span v-if="errors.department_id" class="text-danger">@{{ errors.department_id[0]}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" :class="{'has-error': errors.designation_description}">
                                    <label class="control-label">Description : <span
                                                class="text-danger">*</span></label>
                                    <textarea class="form-control input-sm"
                                              name="designation_description"></textarea>
                                    <span v-if="errors.designation_description"
                                          class="text-danger">@{{ errors.designation_description[0]}}</span>
                                </div>
                            </div>
                        </div>

                        <hr class="short alt">

                        <div class="section row mbn">
                            <div class="col-sm-4 pull-right">
                                <p class="text-left">
                                    <button type="submit" name="save_designation"
                                            class="btn btn-dark btn-gradient dark btn-block"><span
                                                class="glyphicons glyphicons-ok_2"></span> &nbsp; Add New
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
