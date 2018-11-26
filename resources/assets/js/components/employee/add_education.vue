<template>         
<div id="add_education_form" style="max-width:700px" class="popup-basic mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-title">
                <i class="fa fa-rocket"></i>Add New Education
            </span>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" @submit.prevent="AddNewEducation('add_new_education')" id="add_new_education">
                        <div class="row">
                        <input type="hidden" name="user_id" v-model="user_id">
                            <div class="col-md-4">
                                <div class="form-group" :class="{'has-error': errors.education_level_id}">
                                    <label class="control-label">Education Level : <span class="text-danger">*</span></label>
                                    <select class="form-control input-sm" name="education_level_id"
                                            v-on:change="getInstituteAndDegreeByEducationLevelId()"
                                            v-model="education_level_id">
                                        <option v-bind:value="''">---- Select Education Level ----</option>
                                        <option v-bind:value="education_level.id"
                                                v-for="(education_level, index) in education_levels">@{{ education_level.education_level_name }}</option>
                                    </select>
                                    <span v-if="errors.education_level_id" class="text-danger">@{{ errors.education_level_id[0]}}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group" :class="{'has-error': errors.institute_id}">
                                    <label class="control-label">Institute : <span class="text-danger">*</span></label>
                                    <select class="form-control input-sm" name="institute_id">
                                        <option v-bind:value="''">---- Select Institute ----</option>
                                        <option v-bind:value="institute.id"
                                                v-for="(institute, index) in institutes">@{{ institute.institute_name }}</option>
                                    </select>
                                    <span v-if="errors.institute_id" class="text-danger">@{{ errors.institute_id[0]}}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group" :class="{'has-error': errors.degree_id}">
                                    <label class="control-label">Degree : <span class="text-danger">*</span></label>
                                    <select class="form-control input-sm" name="degree_id">
                                        <option v-bind:value="''">---- Select Degree ----</option>
                                        <option v-bind:value="degree.id"
                                                v-for="(degree, index) in degrees">@{{ degree.degree_name }}</option>
                                    </select>
                                    <span v-if="errors.degree_id" class="text-danger">@{{ errors.degree_id[0]}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group" :class="{'has-error': errors.pass_year}">
                                    <label class="control-label">Pass Year : <span class="text-danger">*</span></label>
                                    <input type="text" name="pass_year" class="date form-control input-sm"
                                           readonly="">
                                    <span v-if="errors.pass_year" class="text-danger">@{{ errors.pass_year[0]}}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group" :class="{'has-error': errors.result_type}">
                                    <label class="control-label">Result Type :</label>
                                    <div class="radio-custom mb5">
                                        <input id="result_type_cgpa" name="result_type" type="radio" value="cgpa" checked="checked"
                                               v-on:click="showCgpa=true,showDivision=false">
                                        <label for="result_type_cgpa">CGPA</label>

                                        <input id="result_type_division" name="result_type" type="radio"
                                               value="division" v-on:click="showCgpa=false,showDivision=true">
                                        <label for="result_type_division">Division</label>
                                        <span v-if="errors.result_type" class="text-danger">@{{ errors.result_type[0]}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4" v-if="showCgpa">
                                <div class="form-group" :class="{'has-error' : errors.cgpa}">
                                    <label class="control-label">CGPA : <span class="text-danger">*</span></label>
                                    <input type="number" name="cgpa" class="form-control input-sm" placeholder="Enter CGPA">
                                    <span v-if="errors.cgpa" class="text-danger">@{{ errors.cgpa[0]}}</span>
                                </div>
                            </div>

                            <div class="col-md-4" v-if="showDivision">
                                <div class="form-group" :class="{'has-error': errors.division}">
                                    <label class="control-label">Division : <span class="text-danger">*</span></label>
                                    <select name="division" class="form-control input-sm">
                                        <option value="">--- Select Division ---</option>
                                        <option value="1">First Division</option>
                                        <option value="2">Second Division</option>
                                        <option value="3">Third Division</option>
                                    </select>
                                    <span v-if="errors.division" class="text-danger">@{{ errors.division[0] }}</span>
                                </div>
                            </div>
                        </div>

                        <hr class="short alt">

                        <div class="section row mbn">
                            <div class="col-sm-4 pull-right">
                                <p class="text-left">
                                    <button type="submit" name="save_personal"
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
</template>

<script>
  export default{
    props: ['education_levels','user_id'],
  }
</script>