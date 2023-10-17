$(document).ready(function () {

    getList();
    $("#btnUpdate").hide();
    $("#btnAdd").hide();
    $("#msg").hide();

    $(document).on("click", ".btnsubmit", function(event) {
        event.preventDefault();
        
        let submitButton = $("#btnsubmit");
        submitButton.html('Saving....<i class="fa fa-spin fa-spinner" aria-hidden="true"></i>');

        let data = new FormData($("#frmTask")[0]);
        let value = Object.fromEntries(data.entries());
     
        $.ajax({
            method: "POST",
            url: "/api/save",
            data: value,
            success: (result) => {
                //$("#msg").html(result.message);
                $("#msg").show().html(result.message);
                submitButton.html('Save');
                $("#frmTask").trigger("reset");
                getList();
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                console.log(err.Message);
              }
        });

    })

    $(document).on("click", ".btnEdit", function(event) {
        $("#msg").hide();
        value = $(this).parent().text().split('-');
        id = $(this).attr("data-id");
        $("#taskname").val(value[0].trim());
        $("#status").val(value[1].trim());
        $("#taskid").val(id);

        $("#btnAdd").show();
        $("#btnUpdate").show();
        $("#btnSubmit").hide();
    });

    $(document).on("click", ".btnDelete", function(event) {
        
        id = $(this).attr("data-id");
        $.ajax({
            method: "delete",
            url: "/api/delete/"+id,
            success: (result) => {
                $("#msg").show().html(result.message);
                getList();
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                console.log(err.Message);
              }
        });
       
    });

    $(document).on("click", ".btnAdd", function(event) {
        $("#msg").hide();
        $("#frmTask").trigger("reset");
        $("#btnAdd").hide();
        $("#btnUpdate").hide();
        $("#btnSubmit").show();
    });

    $(document).on("click", ".btnUpdate", function(event) {
        let submitButton = $("#btnUpdate");
        submitButton.html('Saving....<i class="fa fa-spin fa-spinner" aria-hidden="true"></i>');

        let data = new FormData($("#frmTask")[0]);
        let value = Object.fromEntries(data.entries());
        
        let id =  $("#taskid").val();

        $.ajax({
            method: "PUT",
            url: "/api/update/"+id,
            data: value,
            success: (result) => {
                $("#msg").show().html(result.message);
                submitButton.html('Save');
                $("#frmTask").trigger("reset");
                $("#btnAdd").hide();
                $("#btnUpdate").hide();
                $("#btnSubmit").show();
                getList();
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                console.log(err.Message);
              }
        });
    })

    function getList(){
        var li = "";
        $.ajax({
            method: "GET",
            url: "/api/todolist",
            success: (result) => {
                if(result.code == "200"){
                    data = result.data;
                    for ( var i = 0; i < data.length; i++ ) {
                        li += `<li class='list-group-item'>${data[i].task} - ${data[i].status} 
                        <a class="btn btnEdit" data-id="${data[i].id}"><i class="fa fa-edit"></i></a>
                        <a class="btn btnDelete" data-id="${data[i].id}"><i class="fa fa-trash"></i></a>
                        </li>`;
                    }
                    $("#tasklist").html(li);
                }
                else{
                    $("#tasklist").html("No Tasks Found");
                }
                
                
            },
            error: (error) => {
                if(error.status === 422) { // "Unprocessable Entity" - Form data invalid
                    var message = error.responseJSON.errors ? error.responseJSON.errors.comment ?  error.responseJSON.errors.comment[0] : '' : '';
                    submitButton.html('Save');
                }
            }
        });
    }

});