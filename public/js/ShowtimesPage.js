$(function () {

    var buildQuery = {
        "movie": "",
        "rating": [],
        "year": []
    };

    var filtersUsed = {
        "movie": false,
        "rating": false,
        "year": false
    };

    $(document).ready(function() {
        $("#select-year").slider({});
        $.ajax({
            url: "/showtimes/populate-movies",
            type: "GET",

            success: function (data) {
                populate(JSON.parse(data));
            },
            error: function() {
                alert("Something failed");
            }
        });

        $.ajax({
            url: "/showtimes/populate-halls",
            type: "GET",

            success: function (data) {
                console.log(data);
                populateHalls(JSON.parse(data));
            },
            error: function() {
                alert("Something failed");
            }
        });
    });

    function populate(data) {
        console.log(data);
        if (!filtersUsed.movie) {
            populateHelper(data, $("#select-movie"), "Title", false);
            filtersUsed.movie = true;
        }
        if (!filtersUsed.rating) {
            populateHelper(data, $("#select-rating"), "MRating", true);
        }
        if (!filtersUsed.year) {
            populateYear(data);
        }
        populateMovies(data);
    }

    function query() {
        console.log(buildQuery);
        $.ajax({
            url: "/showtimes/populate-movies/filter",
            type: "GET",
            data: buildQuery,
            success: function(data) {
                // console.log(data);
                populate(JSON.parse(data));
                updateDebugFilter();
            },
            error: function() {
                var selector = $("#showcards");
                selector.empty();
                selector.append("<div class='alert alert-danger' role='alert'>Uh oh! No results :(</div>");
            }
        });
    }

    /**
     * Populate the filters here
     */

    function populateYear(data) {
        var selector = $("#select-year");
        var min = data[0]["RYear"];
        var max = data[0]["RYear"];
        for (var i = 0; i < data.length; i++) {
            var year = data[i]["RYear"];
            if (year < min) {
                min = year;
            }
            if (year > max) {
                max = year;
            }
        }
        selector.slider({
            "min": min,
            "max": max,
            "value": [min, max]
        });
        selector.slider("refresh");
    }

    function populateMovies(data) {
        var titlesExist = [];
        var selector = $("#showcards");
        selector.empty();
        for (var i = 0; i < data.length; i++) {
            var temp = data[i].Title + data[i].RYear;
            var id = temp.replace(/\s/g, '');
            if (!titlesExist.includes(id)) {
                selector.append("<div class='well showcards'>" + 
                                        "<h1>" + data[i].Title + "</h1>" + 
                                        "<p>Release year: " + data[i].RYear + "</p>" +
                                        "<p>Rated: " + data[i].MRating + "</p>" +
                                        "<p>Runtime: " + data[i].Length + "</p>" +
                                        "<label>Showtimes</label>" +
                                        "<div id='" + id + "'>" +
                                        "<a class='btn btn-primary time-buttons' href='" + formatURI(data[i]) +  "' role='button'>" + removeSeconds(data[i].STime) + "</a>" +
                                        "</div>" +
                                        "</div>");
                titlesExist.push(id);
            } else {
                $("#" + id).append("<a class='btn btn-primary time-buttons' href='" + formatURI(data[i]) +  "' role='button'>" + removeSeconds(data[i].STime) + "</a>");
            }
        }      
    }

    function removeSeconds(time) {
        var temp = time.split(":");
        return temp[0] + ":" + temp[1];
    }

    function formatURI(data) {
        return "/BuyTickets?Title=" + data.Title + "&HNumber=" + data.HNumber + "&STime=" + data.STime + "&TPrice=" + data.TPrice;
    }

    function populateHelper(data, selector, key, multiple) {
        //doesn't keep duplicates
        var temp = [];
        selector.empty();
        if (!multiple) {
            selector.append('<option></option>'); //blank option to "deselect"
        }
        for (var i = 0; i < data.length; i++) {
            if (!temp.includes(data[i][key])) {
                selector.append('<option>' + data[i][key] + '</option>');
                temp.push(data[i][key]);
            }
        }
        selector.selectpicker("refresh");
    }

    function populateHalls(data) {
        var selector = $("#hall");
        selector.empty();
        for (var i = 0; i < data.length; i++) {
            selector.append("<option data-subtext='Capacity " + data[i].Capacity + " seats'>" + data[i].HNumber + "</option>");
        }
        selector.selectpicker("refresh");
    }

    /**
     * Handle the filters here
     */
    $(document).on("change", "#select-movie", function() { 
        buildQuery.movie = $(this).val();
        buildQuery.rating = [];
        buildQuery.year = [];
        filtersUsed.rating = false;
        filtersUsed.year = false;
        // filtersUsed.movie = true;
        $("#debug-filter").append("<p>!!! Movie filter reset other filters</p>");
        query();
    });

    $(document).on("change", "#select-rating", function() {
        buildQuery.rating = $(this).val();
        filtersUsed.rating = true;
        query();
    });

    $("#select-year").on("slideStop", function (slideEvt) {
        buildQuery.year = slideEvt.value;
        filtersUsed.year = true;
        query();
    });
    
    var numStartTimes = 1;
    $("#addstarttime").click(function() {
        var selector = $("#starttimes");
        selector.append("<div class='form-group'><div><input type='time' class='form-control' id='datetime" + numStartTimes++ + "' required></div></div>")
        $("#addMovieForm").validator("update"); //tell bootstrap form validator that inputs have changed
    });

    /**
     * This function clears all inputs on modal close or click cancel
     */
    $("#addMovie").on("hidden.bs.modal", function(){
        var selector = $("#addMovieForm")
        selector.validator("destroy");
        $(this).find("input, select").not(".btn").val(""); //selects all text inputs except for inputs disguised as buttons and clears
        $(this).find(".selectpicker").selectpicker("refresh"); //must refresh bootstrap selectors after changing selected value
        $("#starttimes").empty(); //clear all the additional starttimes
        numStartTimes = 1; //reset counter
        $("#addMovieForm").validator("update"); //tell bootstrap form validator that inputs have changed
    });

    function updateDebugFilter() {
        var selector = $("#debug-filter");
        selector.append("<p>" + JSON.stringify(buildQuery) + "</p>");
    }
    
});