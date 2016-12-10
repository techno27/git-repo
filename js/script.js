jQuery(document).ready(function($)
{
    $('#ajaxOverwrite').delegate('.title a#toggle_description', 'click', function(){
        $('.description', $(this).parent()).slideToggle('fast');
		return false;
    });

    $('.description').each(function(){
        var description = $(this).html();
        //$(this).html(urlify(description));
    });
    $('.description a').attr('target', '_blank');

    $( "#deadline" ).datepicker({
        dateFormat  : 'yy-mm-dd',
        firstDay    : 1,                 // Start with Monday
        showAnim    : 'clip'
    });

    $('#ajaxOverwrite').delegate('table#tasks_table', 'tasks_table_load', function()
    {
        $('table#tasks_table').tableDnD(
        {
        onDragClass: "myDragClass",
        onDrop: function(table, row) {
            $.ajax({
                url: window.location.href,
                data: 'reorder=' + $.tableDnD.serialize(),
                success: function(answer){
                    $('#ajaxOverwrite').html();
                    $('#ajaxOverwrite').html($('#ajaxOverwrite', answer).html());
                    $('#tasks_table').tableDnDUpdate();
                    $('#tasks_table').trigger('tasks_table_load');
                },
                error: function(data){}
            });
        }
        });
     });
     $('table#tasks_table').trigger('tasks_table_load');

    $('#ajaxOverwrite').delegate('.edit_btn', 'click', function()
    {
        $('#myModal').modal({});

        $('#remove_task').show();

        $.ajax({
            url: window.location.href + '&edit_id=' + $(this).val(),
            dataType: 'json',
            success: function(answer){
                if(answer['id'] !== undefined)           {$('#myModal input[name="id"]').val(answer['id']); }
                if(answer['title'].length !== undefined)        $('#myModal #title').val(answer['title']);
                if(answer['link'].length !== undefined)         $('#myModal #link').val(answer['link']);
                if(answer['site'].length !== undefined)         $('#myModal #site').val(answer['site']);
                if(answer['deadline'].length !== undefined)     $('#myModal #deadline').val(answer['deadline']);
                if(answer['description'].length !== undefined)  $('#myModal #description').val(answer['description']);
                if(answer['small_note'].length !== undefined)   $('#myModal #small_note').val(answer['small_note']);
                if(answer['full_note'].length !== undefined)    $('#myModal #full_note').val(answer['full_note']);
                if(answer['status'].length !== undefined)       $('#myModal #status').val(answer['status']);
            },

            error: function(data){}
        });

        return true;

    });

    $('#new_task').on('click', function(){
        $('#remove_task').hide();
    });

    $('#remove_task').on('click', function()
    {
        $.ajax({
            url: window.location.href + '&remove_id=' + $('input[name="id"]').val(),
            success: function(answer){
                $('#ajaxOverwrite').html('');
                $('#ajaxOverwrite').html($('#ajaxOverwrite', answer).html());
                $('#ajaxOverwrite #tasks_table').tableDnDUpdate();
                $('#ajaxOverwrite #tasks_table').trigger('tasks_table_load');
            },

            error: function(data){}
        });

    });

    $('#save_task').on('click', function()
    {
        var id = $('input[name="id"]').val();
        param = '&save_task=';
        if(id){
            param +=  id;
        }else{
            param += "new";
        }

        $.ajax({
            url: window.location.href + param,
            data:  $("form#modal_form" ).serialize(),
            type: 'POST',
            success: function(answer){
                $('#ajaxOverwrite').html('');
                $('#ajaxOverwrite').html($('#ajaxOverwrite', answer).html());
                $('#ajaxOverwrite #tasks_table').tableDnDUpdate();
                $('#ajaxOverwrite #tasks_table').trigger('tasks_table_load');
            },

            error: function(data){}
        });

    });

    $('#myModal').on('hidden.bs.modal', function (e) {
         $('#myModal input').val('');
         $('#myModal textarea').val('');
         $('#myModal select').val('');
         $('#myModal .nav-tabs li').removeClass('active').first().addClass('active');
         $('#myModal .tab-pane').removeClass('active').first().addClass('active');
    })

    $('#site_list').on('change', function(){
        $('#site').val($(this).val());
    });

    $('#changeUserName').on('click', function(){
        var userNameValue = $('#userNameValue').html();
        if(userNameValue){
            $('input[name="changeUserName"]').val(userNameValue);
        }
        userBlockToggle();
    });

    $('#userChangeForm input[name="cancel"]').on('click', function(){
        userBlockToggle();
    });

    $('#userChangeForm input[name="send"]').on('click', function()
    {
        var userInfoFormSerialize = $("#userInfo form").serialize();
        var userInfoFormUnserialize = $.unserialize(userInfoFormSerialize);
        $.ajax({
            data:  userInfoFormSerialize,
            success: function(answer){
                $('#userNameValue').html(userInfoFormUnserialize.changeUserName);
                var urlVars = $.getUrlVars();
                if(urlVars.user != userInfoFormUnserialize.user){
                    window.location.href = window.location.pathname + '?user=' + userInfoFormUnserialize.user;
                }
            },
            error: function(data){}
        });

        userBlockToggle();

        return false;

    });

    $('#addUser').on('click', function(){
        $('#userInfo').show();
        $('#userNameValue').hide();
        $('#userChangeForm').toggle();
        $('input[name="user"]').show();
        $('#changeUserName').toggle();
    });

});

function urlify(text) {
    var urlRegex = /(https?:\/\/[^\s\<]+)/g;
    return text.replace(urlRegex, function(url) {
        return '<a href="' + url + '">' + url + '</a>';
    })
    // or alternatively
    // return text.replace(urlRegex, '<a href="$1">$1</a>')
}

function userBlockToggle(){
    var userDataDOM = $('#userData');
    var userFormDOM = $('#userChangeForm');
    userDataDOM.toggle();
    userFormDOM.toggle();
}

$.extend({ // ф-ия создающая массив из параметров url
    getUrlVars: function(){
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    },
    getUrlVar: function(name){
        return $.getUrlVars()[name];
    }
});



