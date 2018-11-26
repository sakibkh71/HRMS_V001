Vue.component('v-select', VueSelect.VueSelect);

Vue.component('select2', {
    props: ['options', 'value'],
    template: '<select><slot></slot></select>',
    mounted: function () {
        var vm = this
        $(this.$el)
          .val(this.value)
          // init select2
          .select2({ data: this.options })
          // emit event on change.
          .on('change', function () {
            vm.$emit('input', this.value)
          })
    },
    watch: {
        value: function (value) {
          // update value
          $(this.$el).val(value)
        },
        options: function (options) {
          // update options
          $(this.$el).select2({ data: options })
        }
    },
    destroyed: function () {
        $(this.$el).off().select2('destroy')
    }
})

new Vue({
  el: '#mainDiv',
  data: {
    
    users: [],
    emp_name: '',
    effective_date: '',
    resign_reason: '',
    edit_emp_name: '',
    emp_supervisor: '',
    edit_effective_date: '',
    edit_resign_reason: '',
    hdn_id: '',
    
  },
  mounted(){
    axios.get('/get-employee').then(response => this.users = response.data);
  },
  watch:{
  },
  methods:{
    // applicationInDetails: function(id){

    //   this.emp_name = id;
    // },
    // formatDate: function(date){
    //   var hours = date.getHours();
    //   var minutes = date.getMinutes();
    //   var ampm = hours >= 12 ? 'pm' : 'am';
    //   hours = hours % 12;
    //   hours = hours ? hours : 12; // the hour '0' should be '12'
    //   minutes = minutes < 10 ? '0'+minutes : minutes;
    //   var strTime = hours + ':' + minutes + ' ' + ampm;
    //   return date.getFullYear() + "-" + (date.getMonth()+1) + "-" +date.getDate();
    // },
    // returnOnlyYear: function(date){
    //   var hours = date.getHours();
    //   var minutes = date.getMinutes();
    //   var ampm = hours >= 12 ? 'pm' : 'am';
    //   hours = hours % 12;
    //   hours = hours ? hours : 12; // the hour '0' should be '12'
    //   minutes = minutes < 10 ? '0'+minutes : minutes;
    //   var strTime = hours + ':' + minutes + ' ' + ampm;
    //   return date.getFullYear();
    // },
    
    saveData(e){

        var pathArray = window.location.pathname.split( '/' );
        
        var formData = new FormData(e.target);

        // formData.append('file', document.getElementById('file').files[0]);

        axios.post("/"+pathArray[1]+"/add", formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
        })
        .then((response) => { 

            $( '#create-form-errors' ).html('');

            if(response.data.title == 'error'){
              swal({
                title: response.data.title+"!",
                text: response.data.message,
                type: response.data.title,
                showCancelButton: false,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Done",
                closeOnConfirm: true
              });
            }
            else{
              swal({
                  title: response.data.title+"!",
                  text: response.data.message,
                  type: response.data.title,
                  showCancelButton: false,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "Done",
                  closeOnConfirm: false
              },
              function(){
                
                  location.href=location.href;
              });
            }
              
        })
        .catch((error) => {
            
            if(error.response.status != 200){ //error 422
            
                var errors = error.response.data;

                var errorsHtml = '<div class="alert alert-danger"><ul>';
                $.each( errors , function( key, value ) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></di>';
                $( '#create-form-errors' ).html( errorsHtml );
            }
        });
    },
    showResignStatus: function(id){

      var val = '';

      if(id > 0){
        if(id == 1){
          val = "Pending";
        }
        else if(id == 2){
          val = "Forward";
        }
        else if(id == 3){
          val = "Approved";
        }
        else if(id == 4){
          val = "Cancel";
        }
        else{
          val = "Invalid";
        }
      }
      else{
        val = "Invalid";
      }

      return val;
    },
    resignStatusBtn: function(id){

      var val = '';

      if(id > 0){
        if(id == 1){
          val = "btn-warning";
        }
        else if(id == 2){
          val = "btn-info";
        }
        else if(id == 3){
          val = "btn-success";
        }
        else if(id == 4){
          val = "btn-danger";
        }
        else{
          val = "Invalid";
        }
      }
      else{
        val = "Invalid";
      }

      return val;
    },
    editData(id){

        var pathArray = window.location.pathname.split( '/' );
        // console.log(pathArray[1]+"--"+pathArray[2]);
        axios.get("/"+pathArray[1]+"/edit/"+id,{
        
        })
        .then((response) => {

          this.edit_emp_name = response.data.user_id;
          $('#edit_show_date_diff_msg').html("");
          this.userLeaveType = [];
          this.userHaveLeavs = [];
          this.userTakenLeave = [];
          $('#show_date_diff').html('');
          
          this.hdn_id = response.data.hdn_id;
          
          this.edit_leave_type_id = response.data.leave_type_id;
          this.edit_from_date =response.data.employee_leave_from;
          this.edit_to_date =response.data.employee_leave_to;
          this.edit_date_diff =response.data.employee_leave_total_days;
          this.edit_leave_reason =response.data.employee_leave_user_remarks;
          this.edit_leave_half_or_full =response.data.employee_leave_half_or_full;
          this.edit_leave_contact_address =response.data.employee_leave_contact_address;
          this.edit_leave_contact_number =response.data.employee_leave_contact_number;
          this.edit_employee_leave_noc_required =response.data.employee_leave_noc_required;
          this.edit_passport_no =response.data.employee_leave_passport_no;
          this.edit_responsible_emp =response.data.employee_leave_responsible_person;
          this.emp_supervisor = response.data.employee_leave_supervisor;
          this.leaveStatus = response.data.employee_leave_status;
          if(this.leaveStatus == 2){
            this.want_to_forward = true;
            this.edit_forward_to = response.data.employee_leave_recommend_to;
          }else{
            this.want_to_forward = false;
          }
          
          this.userLeaveType = response.data.user_leave_type;
          this.edit_leave_type = response.data.user_leave_type;
          this.userHaveLeavs = response.data.userHaveLeavs;
          this.userTakenLeaveId = response.data.taken_leave_type_id;
          this.userTakenLeaveName = response.data.taken_leave_type_name;
          this.userTakenLeaveDays = response.data.taken_leave_type_days;
          this.userTakenLeave = response.data.taken_leave_ary;
          this.show_history = response.data.show_history;
          
          var imgg = response.data.employee_leave_attachment;

          if(imgg.length > 0){
            this.edit_file_info = "File already available";
          }
          else{
            this.edit_file_info = '';
          }

          // edit_file_info: '',
          // edit_emp_name: '',
          
        })
        .catch(function (error) {
            
            swal('Error:','Edit function not working','error');
        });
    },
    updateData: function(e){
            
        var formData = new FormData(e.target);
        formData.append('file', document.getElementById('file').files[0]);

        var pathArray = window.location.pathname.split( '/' );

        axios.post("/"+pathArray[1]+"/edit", formData)
        .then(response => { 
           
          swal({
            title: response.data.title+"!",
            text: response.data.message,
            type: response.data.title,
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Done",
            closeOnConfirm: false
          },
          function(){
              location.href=location.href;
          });
        })
        .catch( (error) => {
            var errors = error.response.data;

            var errorsHtml = '<div class="alert alert-danger"><ul>';
            $.each( errors , function( key, value ) {
                errorsHtml += '<li>' + value[0] + '</li>';
            });
            errorsHtml += '</ul></di>';
            $( '#edit-form-errors' ).html( errorsHtml );
        });
    },

    changeStatus: function(id, stat){
        
        var btn_text='';
        var btn_color = '#fffff';
        if(stat == 3){
          btn_text = "Approve";
          btn_color = "#70ca63";
        }else if(stat == 4){
          btn_text = "Cancel";
          btn_color = "#df5640";
        }else{
          btn_text = "Invalid";
        }

        swal({
          title: "Are you sure?",
          text: "You will not be able to recover !",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: btn_color,
          confirmButtonText: "Yes, "+btn_text+" it!",
          closeOnConfirm: false
        },
        function(){
          
            axios.get("/resign/changeStatus/"+id+"/"+stat,{
      
            })
            .then(response => { 
               
              swal({
                title: "Changed !",
                text: "Status changed successfully.",
                type: "success",
                showCancelButton: false,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Done",
                closeOnConfirm: false
              },
              function(){
                  location.href=location.href;
              });
            })
            .catch( (error) => {
                var errors = error.response.data;
                console.log(error);
            });
        });
    },
    chResponsibleStatus: function(id, stat, loginEmp){

        axios.get("/leave/chResponsibleStatus/"+id+"/"+stat+"/"+loginEmp,{
      
        })
        .then(response => { 
           
          swal({
            title: "Changed !",
            text: "Status changed successfully.",
            type: "success",
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Done",
            closeOnConfirm: false
          },
          function(){
              location.href=location.href;
          });
        })
        .catch( (error) => {
            var errors = error.response.data;
            console.log(error);
        });
    }
  }
})