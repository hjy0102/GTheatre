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

    var accType = "";

    $(document).ready(function() {
        $.ajax({
            url: "/showtimes/get-acc",
            type: "GET",

            success: function (data) {
                accType = data.toLowerCase();
            },
            error: function(e) {
                spawnErrorModal("Could not get accType", e);
            }
        });

        $.ajax({
            url: "/showtimes/populate-movies",
            type: "GET",

            success: function (data) {
                populate(JSON.parse(data));
            },
            error: function(e) {
                spawnErrorModal("Could not populate movies", e);
            }
        });

        $.ajax({
            url: "/showtimes/populate-halls",
            type: "GET",

            success: function (data) {
                console.log(data);
                populateHalls(JSON.parse(data));
            },
            error: function(e) {
                spawnErrorModal("Could not populate halls", e);
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
                // updateDebugFilter();
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
            var id = temp.replace(/\s/g, "");
            var stimeid = id + data[i].STime.replace(/:/g, "") + data[i].HNumber;
            if (!titlesExist.includes(id)) {
                selector.append("<div class='well showcards' id='" + id + "'>" + 
                                editDelete(data, id) +
                                "<h1>" + data[i].Title + "</h1>" +
                                "<p>Release year: " + data[i].RYear + "</p>" +
                                "<p>Rated: " + data[i].MRating + "</p>" +
                                "<p>Runtime: " + data[i].Length + "</p>" +
                                "<label>Showtimes</label>" +
                                "<div>" +
                                "<button class='btn btn-primary time-buttons' type='submit' id='" + stimeid + "'>" + removeSeconds(data[i].STime) + "</button>" +
                                "</div>" +
                                "</div>");
                titlesExist.push(id);
                $("#" + stimeid).data(data[i]);
            } else { //showcard exists
                $("#" + id + " > div").append("<button class='btn btn-primary time-buttons' type='submit' id='" + stimeid + "'>" + removeSeconds(data[i].STime) + "</button>");
                $("#" + stimeid).data(data[i]);
            }
            $("#" + id).data(data[i]);
        }      
    }

    function editDelete(data, id) {
        if (accType == "employee") {
            return "<button class='btn btn-danger delete right'>Delete</button><button class='btn btn-default edit right' data-toggle='modal' data-target='#updateMovie'>Edit</button>";
        } else {
            return "";
        }
    }

    function removeSeconds(time) {
        var temp = time.split(":");
        return temp[0] + ":" + temp[1];
    }

    function formatURI(data) {
        return "/BuyTickets?Title=" + data.Title +"&RYear=" + data.RYear + "&HNumber=" + data.HNumber + "&STime=" + data.STime + "&TPrice=" + data.TPrice;
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
        var uselector = $("#uhall");
        selector.empty();
        for (var i = 0; i < data.length; i++) {
            selector.append("<option data-subtext='Capacity " + data[i].Capacity + " seats'>" + data[i].HNumber + "</option>");
            uselector.append("<option data-subtext='Capacity " + data[i].Capacity + " seats'>" + data[i].HNumber + "</option>");
        }
        selector.selectpicker("refresh");
        uselector.selectpicker("refresh");
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

    $(document).on("click", ".time-buttons", function() {
        var data = $(this).data();
        if (accType == "customer") {
            window.location.replace(formatURI(data));
        } else {
            spawnErrorModal("Uh oh :(", "Please login as a customer to continue purchasing a ticket");

        }
    });

    function spawnErrorModal(errorTitle, errorText) {
        $("#errorModal .modal-title").html(errorTitle);
        $("#errorModal .modal-body p").html(errorText);
        if ($('#errorModal').is(':hidden')) {
            $("#errorModal").modal('show')
        }
    }

    $(document).on("click", ".edit", function() {
        var data = $(this).parent().data();
        // alert(JSON.stringify(data));
        $("#utitle").val(data.Title);
        $("#uyear").val(data.RYear);
        $("#urating").selectpicker("val", data.MRating);
        $("#ulength").val(data.Length);
        $("#updateMovie").data(data);
    }); 
    
    $(document).on("click", ".delete", function() {
        var data = $(this).parent().data();
        $.ajax({
            url: "/showtimes/delete-movie",
            type: "POST",
            data: data,
            success: function (d) {
                console.log(d);
                window.location.replace("/showtimes");
            },
            error: function(e) {
                spawnErrorModal("Could not delete movie", e);
            }   
        });
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

    $("#updateMovie").on("submit", function() {
        data = {
            Title: $("#utitle").val(),
            RYear: $("#uyear").val(),
            MRating: $("#urating").val(),
            Length: $("#ulength").val(),
            keyTitle: $(this).data().Title,
            keyRYear: $(this).data().RYear
        }
        $.ajax({
            url: "/showtimes/update-movie",
            type: "POST",
            data: data,
            success: function () {
                //
            },
            error: function(e) {
                spawnErrorModal("Could not update movie", e);
            }   
        });
    });

    $("#addMovie").on("submit", function() {
        data = {
            Title: $("#title").val(),
            RYear: $("#ayear").val(),
            MRating: $("#arating").val(),
            Length: $("#length").val(),
            HNumber: $("#hall").val(),
            STimes: []
        };
        
        data.STimes.push({STime: $("#datetime0").val(), ETime: ""});
        for (var i = 1; i < $("#starttimes div").length - 2; i++) {
            data.STimes.push({STime: $("#datetime" + i).val(), ETime: ""});
        }

        for (var j = 0; j < data.STimes.length; j++) {
            data.STimes[j].ETime = addMinutes(data.STimes[j].STime, data.Length + 15);
            data.STimes[j].STime += ":00";
            data.STimes[j].ETime += ":00";
        }

        // alert(JSON.stringify(data));

        $.ajax({
            url: "/showtimes/add-movie",
            type: "GET",
            data: data,
            success: function (d) {
                //
                console.log(d);
            },
            error: function(e) {
                console.log(e);
                spawnErrorModal("Could not add movie", e);
            }   
        });
        
    });

    function addMinutes(time, minsToAdd) {
        function z(n){
            return (n<10? '0':'') + n;
        }
        var bits = time.split(':');
        var mins = bits[0]*60 + (+bits[1]) + (+minsToAdd);
        return z(mins%(24*60)/60 | 0) + ':' + z(mins%60);  
    }  

    function updateDebugFilter() {
        var selector = $("#debug-filter");
        selector.append("<p>" + JSON.stringify(buildQuery) + "</p>");
    }


    /**
     * hack to simulate extending layout
     */
    $("#signout-link").click(function () {
      console.log("sign out now!");
      $.ajax({
          url: "/signout",
          type: "POST",
          success: function () {
            window.location.replace("/");
          }
      });
    });
    
});