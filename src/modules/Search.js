import $ from "jquery";

class Search {
  // 1. describe and create/initiate our object
  constructor() {
    this.addSearchHTML();
    this.resultsDiv = $(".search-overlay__results");
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    this.searchInput = $("#search-term");
    this.events();
    this.isOverlayOpen = false;
    this.isSpinnerVisible = false;
    this.previousValue;
    this.typingTimer;
  }
  // 2.events listeners
  events() {
    this.openButton.on("click", this.openOverlay.bind(this));
    this.closeButton.on("click", this.closeOverlay.bind(this));
    $(document).on("keydown", this.keyPressDispatcher.bind(this));
    this.searchInput.on("keyup", this.typingLogic.bind(this));
  }

  // 3.methods (function or an action)
  typingLogic() {
    if (this.searchInput.val() != this.previousValue) {
      clearTimeout(this.typingTimer); //reset a time out
      if (this.searchInput.val()) {
        if (!this.isSpinnerVisible) {
          this.resultsDiv.html('<div class="spinner-loader"></div>');
          this.isSpinnerVisible = true;
        }
        this.typingTimer = setTimeout(this.getResults.bind(this), 750);
      } else {
        this.resultsDiv.html("");
        this.isSpinnerVisible = false;
      }
    }
    this.previousValue = this.searchInput.val();
  }
  getResults() {
    $.getJSON(
      `${
        universityData.root_url
      }/wp-json/university/v1/search?term=${this.searchInput.val()}`,
      (results) => {
        // console.log(results.generalInfo);
        this.resultsDiv.html(`
            <div class="row">
                <div class="one-third">
                    <h2 class="search-overlay__section-title">General Information</h2>
                    ${
                      results.generalInfo.length
                        ? '<ul class="link-list min-list">'
                        : "<p>No general information matches the search</p>"
                    }
                    ${results.generalInfo
                      .map(
                        (item) => `
                    <li>
                        <a href="${item.permalink}">${item.title} </a>${
                          item.postType == "post" ? `by ${item.author}` : ""
                        }
                    </li>`
                      )
                      .join("")}
                        
                    ${results.generalInfo.length ? "</ul>" : ""}
                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title">Programs</h2>
                    ${
                      results.programs.length
                        ? '<ul class="link-list min-list">'
                        : `<p>No progams matches the search 
                          <a href="${universityData.root_url}/programs">View all programs</a>
                          </p>`
                    }
                      ${results.programs
                        .map(
                          (item) => `
                      <li>
                          <a href="${item.permalink}">${item.title} </a>${
                            item.postType == "post" ? `by ${item.author}` : ""
                          }
                      </li>`
                        )
                        .join("")}
                          
                      ${results.programs.length ? "</ul>" : ""}
                    <h2 class="search-overlay__section-title">Professors</h2>
                    ${
                      results.profesors.length
                        ? '<ul class="professor-cards">'
                        : `<p>No professors matches the search 
                          </p>`
                    }
                      ${results.profesors
                        .map(
                          (item) => `
                          <li class="professor-card__list-item">
                            <a class="professor-card" href="${item.permalink}">
                                <img class="professor-card__image" src="${item.thumbnail}" alt="">
                                <span class="professor-card__name">${item.title}</span>
                            </a>
                         </li>
                      `
                        )
                        .join("")}
                          
                      ${results.profesors.length ? "</ul>" : ""}
                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title">Events</h2>
                    ${
                      results.events.length
                        ? ''
                        : `<p>No events matches the search 
                          <a href="${universityData.root_url}/events">View all Events</a>
                          </p>`
                    }
                      ${results.events
                        .map(
                          (item) => `
                          <div class="event-summary">
                            <a class="event-summary__date event-summary__date--beige t-center" href="${item.permalink}">
                                <span class="event-summary__month">${item.month}</span>
                                <span class="event-summary__day">${item.day}</span>
                            </a>
                            <div class="event-summary__content">
                                <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                                <p>
                                ${item.excerpt }
                                <a href="${item.permalink}" class="nu gray">Read more</a></p>
                            </div>
                        </div>
                          `
                        )
                        .join("")}
                          
                </div>
            </div>
            `);
      }
    );
  }
  openOverlay() {

    this.searchOverlay.addClass("search-overlay--active");
    $("body").addClass("body-no-scroll");
    this.searchInput.val("");
    setTimeout(() => this.searchInput.trigger("focus"), 301);
    this.isOverlayOpen = true;
    return false;
  }

  closeOverlay() {
    this.searchOverlay.removeClass("search-overlay--active");
    $("body").removeClass("body-no-scroll");
    this.resultsDiv.html("");
    this.isOverlayOpen = false;
  }

  keyPressDispatcher(e) {
    if (
      e.keyCode === 83 &&
      !this.isOverlayOpen &&
      !$("input, textarea").is(":focus")
    ) {
      this.openOverlay();
    } else if (e.keyCode === 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }
  }

  addSearchHTML() {
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

export default Search;
