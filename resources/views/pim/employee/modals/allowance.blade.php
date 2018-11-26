
    <div id="add_more_allownce_modal" style="max-width:650px" class="popup-basic mfp-with-anim mfp-hide">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">
                    <i class="fa fa-rocket"></i> Add Other Allowance
                </span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="add_more_allowance" method="post" v-on:submit.prevent="addMoreAllowance('add_more_allowance')">
                            <div class="row">
                                <div class="col-md-4" v-for="levelSalaryNotinLevel in levelSalaryNotinLevels">
                                    <div class="checkbox-custom mb5 pull-left">
                                        <input :id="levelSalaryNotinLevel.id" type="checkbox" :name="'otherAllowance['+levelSalaryNotinLevel.salary_info_name+']'" :value="levelSalaryNotinLevel.id">

                                        <label :for="levelSalaryNotinLevel.id" v-text="(levelSalaryNotinLevel.amount_status==0)?levelSalaryNotinLevel.salary_info_name+' (%) ':levelSalaryNotinLevel.salary_info_name+' ($) '"></label>
                                    </div>
                                </div>
                            </div>

                            <hr class="short alt">

                            <div class="section row mbn">
                                <div class="col-sm-4 pull-right">
                                    <p class="text-left">
                                        <button type="submit" name="add_allowance" class="btn btn-dark btn-gradient dark btn-block"><span class="glyphicons glyphicons-ok_2"></span> &nbsp; Add Allowance
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