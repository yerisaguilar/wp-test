import $ from 'jquery';

class MyNotes {
    constructor() {
        this.events();
    }
    
    events() {
        $("#my-notes").on("click", ".delete-note",this.deleteNote);
        $("#my-notes").on("click", ".edit-note",this.editNote.bind(this));
        $("#my-notes").on("click", ".update-note",this.updateNote.bind(this));
        $(".submit-note").on("click",this.createNote.bind(this));
        
       
    }

    //Methods will go here
    createNote(e){
        const title =  document.getElementById("new-note-title");
        const content = document.getElementById("new-note-body");
        const ourNewPost = {
            'title':title.value,
            'content' : content.value,
            'status': 'publish'
        }
        
            $.ajax({
                beforeSend: (xhr)=>{
                    xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
                },
                url: universityData.root_url+'/wp-json/wp/v2/note/',
                type: 'POST',
                cache: false,
                data: ourNewPost,
                success: (response)=>{
                    $(".new-note-title, .new-note-body").val('');
                    $(`
                    <li data-id="${response.id}">
                        <input readonly class="note-title-field" type="text" value="${response.title.raw}">
                        <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
                        <span class="delete-note" ><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
                    
                        <textarea readonly class="note-body-field" >
                            ${response.content.raw}
                        </textarea>
                        <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
                        
                    </li>
                    `).prependTo("#my-notes").hide().slideDown();

                    console.log("congrats");
                    console.log(response);
                },
                error:(response)=>{
                    console.log("error");
                    console.log(response);
    
                }
            }); 
        }


    deleteNote(e){
    //    const thisNote = e.target;
    const thisNote = $(e.target).parents("li");
        $.ajax({
            beforeSend: (xhr)=>{
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url+'/wp-json/wp/v2/note/'+thisNote.data('id'),
            type: 'DELETE',
            cache: false,
            
            success: (response)=>{
                thisNote.slideUp();
                console.log("congrats");
                console.log(response);
            },
            error:(response)=>{
                console.log("error");
                console.log(response);

            }
        });
    }

    updateNote(e){
        //    const thisNote = e.target;
        const thisNote = $(e.target).parents("li");
        console.log(thisNote);
        const ourUpdatedPost = {
            'title': thisNote.find(".note-title-field").val(),
            'content': thisNote.find(".note-body-field").val()

        }
        // console.log(ourUpdatedPost);
        
            $.ajax({
                beforeSend: (xhr)=>{
                    xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
                },
                url: universityData.root_url+'/wp-json/wp/v2/note/'+thisNote.data('id'),
                type: 'POST',
                cache: false,
                data: ourUpdatedPost,
                success: (response)=>{
                    
                    this.makeNoteReadOnly(thisNote);
                    console.log("congrats");
                    console.log(response);
                },
                error:(response)=>{
                    console.log("error");
                    console.log(response);
    
                }
            });
        }

    editNote(e){
        const thisNote = $(e.target).parents("li");
        // const isclicked = true;
        if (thisNote.data("state") == "editable"){
            this.makeNoteReadOnly(thisNote);
        }else {
            this.makeNoteEditable(thisNote);
        }
      /*
        $.ajax({
            beforeSend: (xhr)=>{
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url : universityData.root_url+'/wp-json/w[/v2/note/'+thisNote.data('id'),
            type: 'UPDATE',
            data: data,
            chache: false,
            success: (response)=>{
                console.log('Item updated');
                console.log(response);
            },
            error:(response)=>{
                console.log("error");
                console.log(response);
            }
        });*/
    }
    makeNoteEditable(thisNote){
        thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i>Cancel');
        thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field");
        thisNote.find(".update-note").addClass("update-note--visible");
        thisNote.data("state", "editable");
        
    }
    
      makeNoteReadOnly(thisNote) {
        thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit')
        thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field")
        thisNote.find(".update-note").removeClass("update-note--visible")
        thisNote.data("state", "cancel")
      }
}
export default MyNotes;