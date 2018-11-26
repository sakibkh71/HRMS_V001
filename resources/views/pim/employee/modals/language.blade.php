<div id="add_new_language_modal" style="max-width:700px" class="popup-basic mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-title" v-if="singleLanguage !=''">
                <i class="fa fa-rocket"></i>Edit Language
            </span>
            <span v-else class="panel-title">
                <i class="fa fa-rocket"></i>Add New Language
            </span>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <form id="add_new_language_form" method="post" v-on:submit.prevent="addNewLanguage">
                        <input type="hidden" name="user_id" v-model="user_id">
                        <input type="hidden" name="id" v-if="singleLanguage !=''" v-model="singleLanguage.id">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" :class="{'has-error': errors.language_id}">
                                    <label class="control-label">Language Name : <span class="text-danger">*</span></label>
                                    <select name="language_id" class="form-control input-sm" v-model="singleLanguage.language_id">
                                        <option value="">...Select Language Name...</option>
                                        <option v-for="lan in language" :value="lan.id" v-text="lan.language_name"></option>
                                    </select>
                                    <span v-if="errors.language_id" class="text-danger">@{{ errors.language_id[0]}}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group" :class="{'has-error': errors.speaking}">
                                    <label class="control-label">Speaking Skill : <span class="text-danger">*</span></label>
                                    <select name="speaking" class="form-control input-sm" v-model="singleLanguage.speaking">
                                        <option value="">...Select Speaking Skill...</option>
                                        <option>Bad</option>
                                        <option>Medium</option>
                                        <option>Good</option>
                                        <option>excelent</option>
                                    </select>
                                    <span v-if="errors.speaking" class="text-danger">@{{ errors.speaking[0]}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" :class="{'has-error': errors.reading}">
                                    <label class="control-label">Reading Skill : <span class="text-danger">*</span></label>
                                    <select name="reading" class="form-control input-sm" v-model="singleLanguage.reading">
                                        <option value="">...Select Reading Skill...</option>
                                        <option>Bad</option>
                                        <option>Medium</option>
                                        <option>Good</option>
                                        <option>excelent</option>
                                    </select>
                                    <span v-if="errors.reading" class="text-danger">@{{ errors.reading[0]}}</span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group" :class="{'has-error': errors.writing}">
                                    <label class="control-label">Writing Skill : <span class="text-danger">*</span></label>
                                    <select name="writing" class="form-control input-sm" v-model="singleLanguage.writing">
                                        <option value="">...Select Writing Skill...</option>
                                        <option>Bad</option>
                                        <option>Medium</option>
                                        <option>Good</option>
                                        <option>excelent</option>
                                    </select>
                                    <span v-if="errors.writing" class="text-danger">@{{ errors.writing[0]}}</span>
                                </div>
                            </div>
                        </div>

                        <hr class="short alt">

                        <div class="section row mbn">
                            <div class="col-sm-4 pull-right">
                                <p class="text-left">
                                    <button v-if="singleLanguage !=''" type="submit" name="save_language" class="btn btn-dark btn-gradient dark btn-block"><span class="glyphicons glyphicons-ok_2"></span> &nbsp; Update Language
                                    </button>
                                    <button v-else type="submit" name="save_language" class="btn btn-dark btn-gradient dark btn-block"><span class="glyphicons glyphicons-ok_2"></span> &nbsp; Add New
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