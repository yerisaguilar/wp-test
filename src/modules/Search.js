import $ from 'jquery';

class Search {
    // 1. describe and create/initiate our object
    constructor() {
        this.addSearchHTML();
        this.resultsDiv = $(".search-overlay__results");
        this.openButton =$(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.searchInput = $("#search-term");
        this.events();
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;
        this.previousValue;
        this.typingTimer ;

    }
    // 2.events listeners
    events(){
        this.openButton.on("click",this.openOverlay.bind(this));
        this.closeButton.on("click",this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));
        this.searchInput.on("keyup", this.typingLogic.bind(this));
    }

    // 3.methods (function or an action)
    typingLogic(){
        if(this.searchInput.val() != this.previousValue){
            clearTimeout(this.typingTimer); //reset a time out
            if(this.searchInput.val()){
                if(!this.isSpinnerVisible){
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this),750);
            }else{
                this.resultsDiv.html('');
                this.isSpinnerVisible = false;
            }
            

        }
        this.previousValue = this.searchInput.val();

    }
    getResults(){
        $.when(
            $.getJSON(`${universityData.root_url}/wp-json/university/v1/search?term=${this.searchInput.val()}`),
        ).then( (posts) => {
                var combinedResults = posts[0];
                this.resultsDiv.html(`
                <h2 class="search-overlay__section-title">General Information</h2>
                ${combinedResults.length? '<ul class="link-list min-list">': '<p>No general information matches the search</p>'}
                ${combinedResults.map(item =>`
                <li>
                    <a href="${item.link}">${item.title.rendered} </a>${(item.type == 'post')? `by ${item.authorName}` : ""}
                </li>`).join('')
                }
                    
                ${combinedResults.length? '</ul>': ''}`);
                this.isSpinnerVisible = false;

        }, () =>{
            this.resultsDiv.html('<p>Unexpected error: please try again</p>')
        }
            
        );
    }
    openOverlay(){
         this.searchOverlay.addClass("search-overlay--active");
         $("body").addClass("body-no-scroll");
         this.searchInput.val('');
         setTimeout(() => this.searchInput.trigger('focus'), 301);
         this.isOverlayOpen = true;

    }

    closeOverlay(){
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        this.resultsDiv.html("");
        this.isOverlayOpen = false;

    }

    keyPressDispatcher(e){
        if(e.keyCode === 83 && !this.isOverlayOpen && !$("input, textarea").is(':focus')){
            this.openOverlay();
        }else if(e.keyCode === 27 && this.isOverlayOpen){
            this.closeOverlay();
        }
    }

    addSearchHTML(){
        $("body").append(`
        <div class="search-overlay ">
            <div class="search-overlay__top">
            <div class="container">
                <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                <input autocomplete="off" type="text" name="" id="search-term" class="search-term" placeholder="What are you looking for?">
                <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
            </div>
            </div>
            <div class="container">
            <div class="search-overlay__results">
                
            </div>
            </div>
        </div>`);
    }

  }
  
  export default Search
  