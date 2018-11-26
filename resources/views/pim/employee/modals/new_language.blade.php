<div id="add_new_language_button_modal" style="max-width:400px" class="popup-basic mfp-with-anim mfp-hide">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">
                    <i class="fa fa-rocket"></i>Add New Language
                </span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="add_language" method="post" v-on:submit.prevent="addLanguage('add_language')">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" :class="{'has-error': errors.language_name}">
                                        <label class="control-label">Language Name : <span class="text-danger">*</span></label>
                                        <input type="text" name="language_name" class="form-control input-sm">
                                        <span v-if="errors.language_name" class="text-danger">@{{ errors.language_name[0]}}</span>
                                    </div>
                                </div>
                            </div>

                            <hr class="short alt">

                            <div class="section row mbn">
                                <div class="col-sm-6 pull-right">
                                    <p class="text-left">
                                        <button type="submit" name="add_language" class="btn btn-dark btn-gradient dark btn-block"><span class="glyphicons glyphicons-ok_2"></span> &nbsp; Add Language
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