import $ from 'jquery';

class Search {
    // 1. describe and create/initiate our object
    constructor() {
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
                this.typingTimer = setTimeout(this.getResults.bind(this),2000);
            }else{
                this.resultsDiv.html('');
                this.isSpinnerVisible = false;
            }
            

        }
        this.previousValue = this.searchInput.val();

    }
    getResults(){
        //WP Rest API
        $.getJSON(`${universityData.root_url}/wp-json/wp/v2/posts?search=${this.searchInput.val()}`,  posts =>{
            // alert(posts[0].title.rendered);
            this.resultsDiv.html(`<h2 class="search-overlay__section-title">General Information</h2>
            ${posts.length? '<ul class="link-list min-list">': '<p>No general information matches the search</p>'}
            ${posts.map(item =>`
            <li>
                <a href="${item.link}">${item.title.rendered}</a>
            </li>`).join('')
            }
                
            ${posts.length? '</ul>': ''}`);
            this.isSpinnerVisible = false;
        });
    }
    openOverlay(){
         this.searchOverlay.addClass("search-overlay--active");
         $("body").addClass("body-no-scroll");
         this.isOverlayOpen = true;

    }

    closeOverlay(){
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        this.searchInput.val("");
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

  }
  
  export default Search
  