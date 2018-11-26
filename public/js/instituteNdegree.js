new Vue({
    el: '#mainDiv',
    data:{
      institute_name: null,
      hdn_id: null,
      ins_degree_name: null,
      institute_or_degree: null,
    },
    methods:{

      saveInstitute: function(formId){

          var formData = $('#'+formId).serialize();

          axios.post('/Institute_n_Degree/add', formData)
          .then((response) => { 

              $('.create-form-errors').html('');
              
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
                  $( '.create-form-errors' ).html( errorsHtml );
              }
          });
      },  

      saveDegree: function(formId){

          var formData = $('#'+formId).serialize();

          axios.post('/Institute_n_Degree/add', formData)
          .then((response) => { 

              $('#create-form-errors').html('');
              
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

      dataEdit: function(id, typee){

        axios.get('/Institute_n_Degree/edit/'+id+'/'+typee,{
          
        })
        .then((response) => {
            
            this.ins_degree_name = response.data.name;
            this.hdn_id = response.data.id;
            this.institute_or_degree = response.data.institute_or_degree;
        })
        .catch(function (error) {
           
            swal('Error:','Delete function not working','error');
        });
      },

      updateData: function(formId){

          var formData = $('#'+formId).serialize();

          axios.post('/Institute_n_Degree/edit', formData)
          .then((response) => { 

              $('#edit-form-errors').html('');
              
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
                  $( '#edit-form-errors' ).html( errorsHtml );
              }
          });
      },

      deleteInstitute: function(id){

          swal({
              title: "Are you sure?",
              text: "You will not be able to recover this information!",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Yes, delete it!",
              closeOnConfirm: false
          },
          function(){
              
              swal("Deleted!", "Your imaginary file has been deleted.", "success");
              axios.get('/Institute_n_Degree/delete/'+id+'/institute',{
          
              })
              .then((response) => {
                  
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
              .catch(function (error) {
                 
                  swal('Error:','Delete function not working','error');
              });
          });
      },

      deleteDegree: function(id){

          swal({
              title: "Are you sure?",
              text: "You will not be able to recover this information!",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Yes, delete it!",
              closeOnConfirm: false
          },
          function(){
              
              swal("Deleted!", "Your imaginary file has been deleted.", "success");
              axios.get('/Institute_n_Degree/delete/'+id+'/degree',{
          
              })
              .then((response) => {
                  
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
              .catch(function (error) {
                 
                  swal('Error:','Delete function not working','error');
              });
          });
      }    
    }
  });