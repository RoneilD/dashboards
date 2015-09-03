$(document).ready(function(e) {
	var time = new Date().getTime();
	var timeout = 600000; //10 minutes of inacity
	
	init();


	//sidebar menu open/close
	$("body").on("click", "a.menu", function(){
	
		var page = $("#page");
	
		if(page.hasClass("menu")){
		
			page.removeClass("menu");
		
		}
		else{
		
			page.addClass("menu");
			
		}
	
		return false;
		
	});
	
	/*
	 * Open the dropdowns on click
	 *
	 */
	$("body").on("click", "#changeDuration, #addOverview", function(){
		var parent = $(this).parent();
		
		if (parent.hasClass("open")) {
			
			
			parent.removeClass("open");
			
		}
		else{
			
			$(".dropdown").scrollTop();
			
			$(".control").removeClass("active").removeClass("open");
			$("#dashboardBuilder .dashboardBuilderMain").removeClass("open");
			$("#addComparison").empty().append("Add Comparison <i class='fa fa-caret-down'></i>");	
			
			parent.addClass("open");
			
		}
		
		return false;
		
	});
	
	/*
	 * Open the fleet group selector dropdown
	 *
	 */
	$("body").on("click", "#addComparison", function(){
		var parent = $(this).parent();
		
		if (parent.hasClass("open")) {
			
			
			parent.removeClass("open");
			
		}
		else{
			
			$(".control").removeClass("open");
			
			$("p span.totalSelections").empty().append($("#dashboardBuilder .fleetsDropdown li.selected").length);
			
			parent.addClass("open");
			
		}
		
		return false;
		
	});
	
	/*
	 * Add a new slide to the builder
	 *
	 */
	$("body").on("click", "ul.overviewFleetSelector li, #addBlackout, #dashboardBuilder .dashboardOptions .dropdown li.entire", function(){
		
		var id = $(this).data("id");
		var name = $(this).data("name");
		var type = $(this).data("type");
		var count = parseInt($("a.delete").eq(-2).data("id"), 10);
		count = count + 1;
		
		//get the hidden div and append it to the dashboard list
		$(".dashboardList .inner div:last").before($(".hidden").html());

		//set the values
		var hidden = $(".dashboardList .dashboard").last();
		hidden.attr("id", "d"+count);
		hidden.find(".dTitle p").empty().append(name);
		hidden.find(".delete").attr("data-id", count);
		hidden.find(".status").removeAttr("name").attr("name", "conf[pattern]["+count+"][status]");
		hidden.find(".fleetid").val(id).removeAttr("name").attr("name", "conf[pattern]["+count+"][fleetid]");
		hidden.find("p.type").empty().append(type);
		
		$("#addComparison").parent().removeClass("active").removeClass("open");
		
		$(".control").removeClass("open");
		$("#dashboardBuilder .dashboardBuilderMain").removeClass("open");
		$("#addComparison").empty().append("Add Comparison <i class='fa fa-caret-down'></i>");	
		
	});
	
	/*
	 * Fleet group selected, show fleets under this group
	 *
	 */
	$("body").on("click", ".dashboardOptions .dropdown li.normal", function(){
		var id = $(this).data("id");
		var name = $(this).text();
		var wrapper = $("#dashboardBuilder .dashboardBuilderMain");
		
		if (wrapper.hasClass("open") && !$("#addComparison").parent().hasClass("active")) {
			
			$("#addComparison").empty().append("Add Comparison <i class='fa fa-caret-down'></i>");	
			
			$(this).parent().removeClass("open");
		
			wrapper.removeClass("open");	
		}
		else{
			//hide all fleets
			$(".fleetsSelector li").addClass("hide").removeClass("show");
			//show fleets under selected group
			$(".fleetsSelector li[data-group='"+name+"']").removeClass("hide").addClass("show");
			
			//set the fleet group name to the button
			$("#addComparison").empty().append(name+' <i class="fa fa-caret-down"></i>');	
			
			$(".control").removeClass("open");
			
			$("#addComparison").parent().addClass("active");
			
			//reset 
			//$("p span.totalSelections").empty().append("0");
			$("p span.totalOptions").empty().append($(".fleetsSelector li.show").length );
			
			wrapper.addClass("open");			
		}
		
		return false;
		
	});
	
	
	/*
	 * The fleet selection click.
	 * Check total amount of fleets that have the "selected" class
	 * Limit to 9
	 *
	 */
	$("body").on("click", "#dashboardBuilder .fleetsDropdown li", function() {
			//$('li').removeClass("selected");
			
			if ($(this).hasClass("selected")) {
				
				$(this).removeClass("selected");
				
			}
			else{
				
				if ($("#dashboardBuilder .fleetsDropdown li.selected").length <= 8) {
					
					$(this).addClass("selected");
				
				}

			}
			
			$("p span.totalSelections").empty().append($("#dashboardBuilder .fleetsDropdown li.selected").length);
			
			
	});	
	
	/*
	 * Set the value for slide duration
	 *
	 */
	$("body").on("click", "ul.durationSelector li", function(){
		
		var value = $(this).data("value");
		
		$(".control").removeClass("open");
		
		$("#changeDuration").empty().append(value+' sec <i class="fa fa-caret-down"></i>');
		
		$("input.inpDuration").val(value);
		
	});
	
	/*
	 * remove a slide from the builder
	 *
	 */
	$("body").on("click", "a.delete", function(){

		var id = $(this).data("id");
		
		$("#d"+id).remove();
	
		return false;
	
	});
	
	/*
	 * Create a new comparison chart and add to dashboards
	 *
	 */
	$("body").on("click", "#dashboardBuilder .comparisonSelector a.submit", function(){
		var selected = "";
		var slider_name = $("input[name=sliderName]").val();
		var type = "Comparison";
		var id = 101;

		if (slider_name.length < 1) {
			
			alert("Please name this custom slider");	
			
		}
		else if ($("#dashboardBuilder .fleetsDropdown li.selected").length < 1) {
			
			alert("Please add fleets to this slider");
			
		}
		else{

			var count = parseInt($("a.delete").eq(-2).data("id"), 10);
			count = count + 1;
			
			$("#dashboardBuilder .fleetsDropdown li.selected").each(function(){
				selected += $(this).data("id")+",";
			});
			
			
			$(".control").removeClass("active").removeClass("open");
			$("#dashboardBuilder .dashboardBuilderMain").removeClass("open");
			$("#addComparison").empty().append("Add Comparison <i class='fa fa-caret-down'></i>");			
			
			
			/*
			alert("Slider Name: "+slider_name+"\n\n\n"+"Selected fleets:\n\n"+selected);
			$(".dashboardList .inner div:last").before($(".hidden").html());
		
			var hidden = $(".dashboardList .dashboard").last();
			hidden.attr("id", "d"+count);
			hidden.find(".dTitle p").empty().append(slider_name);
			hidden.find(".delete").attr("data-id", count);
			hidden.find(".status").removeAttr("name").attr("name", "conf[pattern]["+count+"][status]");
			hidden.find(".fleetid").val(id).removeAttr("name").attr("name", "conf[pattern]["+count+"][fleetid]");
			hidden.find("p.type").empty().append(type);
			*/
			
			selected = selected.replace(/[,]$/,'');
	
			$("input[name='conf[slide_name]']").val(slider_name);
			$("input[name='conf[fleet_ids]']").val(selected);
			// $(".customPostForm").submit();
			// Rather send by Ajax than a physical POST (page reload)
			$.post('/Maxine/?savecustomslide', $(".customPostForm").serialize());
			// Now we need to get the data from the server
			$.getJSON('/?getusersliders').done(function ( data ) {
					if ((typeof data[0] != 'undefined') && data[0] == "Getting data failed")
					{
						console.log('Ajax Get failed');
						alert('Something went wrong on save. Please reload page by pressing the F5 key.');
					}
					else
					{
						var html='', i=$('.dashboardList .inner .dashboard .comparison').length+1, id=data[0].id,j=0,count=data.length, fleets='';
						html += '<div class="dashboard comparison" id="d'+id+'">';
						html += '<p class="type">Comparison</p>';
						html += '<a href="#" class="drag"></a>';
						html += '<a href="#" class="delete" data-id="'+id+'"></a>';
						html += '<div class="dTitle">';
						//: Hidden inputs
						for (j=0;j<count;j++)
						{
							fleets += data[j].fleet_id+',';
						}
						fleets = fleets.substring(0, fleets.length-1);
						html += '<input type="hidden" class="slidefleets" value="'+fleets+'"/>';
						html += '<input type=hidden id="status'+id+'" class="status" name="conf[pattern]['+id+'][status]" value="1">';
						html += '<input type=hidden class="fleetid"  name="conf[pattern]['+id+'][fleetid]" value="'+id+'">';
						//: End
						html += '<p>'+data[0].name+'</p>';
						html += '<span class="fleetcount">'+count+'</span>';
						html += '</div>';
						html += '</div>';
						$('.dashboardList .inner').append(html);	
					}
			});
		}
		
		
	});
	
	$("body").on("click", "#dashboardBuilder .dashboard.comparison .dTitle", function(){
		var wrapper = $("#dashboardBuilder .dashboardBuilderMain");
		var fleets = $(this).find(".slidefleets").val(),arr = fleets.split(','),i,totalElements=$('#dashboardBuilder .dashboardBuilderMain .comparisonSelector .fleetsDropdown li');
		
		$("input[name=sliderName]").val($(this).find("p").text());
		$("input[name='conf[slide_id]']").val($(this).find(".fleetid").val());
					
		for(i in arr){
			$("#dashboardBuilder .dashboardBuilderMain .comparisonSelector .fleetsDropdown li[data-id="+arr[i]+"]").addClass("selected").addClass("show");
		}
		for (i in totalElements)
		{
			var test=totalElements[i].className;
			if (test == 'hide')
			{
				totalElements[i].setAttribute('class', 'show');
			}
		}
		
		//update the fleet count
		$("p span.totalSelections").empty().append($("#dashboardBuilder .fleetsDropdown li.selected").length);
	
		//
		$("#addComparison").parent().addClass("active");
		
		wrapper.addClass("open");					
		
		return false;
	});
	
	$("body").on("click", "#dashboardBuilder .closeComparisonSelector", function(){
		
		$(".control").removeClass("open").removeClass("active");
		$("#dashboardBuilder .dashboardBuilderMain").removeClass("open");
		$("#addComparison").empty().append("Add Comparison <i class='fa fa-caret-down'></i>");
		
		//reset hidden values so user can create a new custom slider
		$("input[name='conf[slide_id]']").val("");
		$("input[name='sliderName']").val("My fleet name");
		$("p span.totalSelections").empty().append("0");
		
		$("#dashboardBuilder .fleetsDropdown li.selected").removeClass("show").removeClass("selected");
		
		
		return false;
		
	});

	
	//check if user has clicked anywhere else on the page
	$(document).click(function(e) {
	
		if(!$(e.target).parents().andSelf().is(".control, .comparisonSelector")) {

			$(".control").removeClass("open").removeClass("active");
			$("#dashboardBuilder .dashboardBuilderMain").removeClass("open");
			$("#addComparison").empty().append("Add Comparison <i class='fa fa-caret-down'></i>");
			
			//reset hidden values so user can create a new custom slider
			$("input[name='conf[slide_id]']").val("");
			$("input[name='sliderName']").val("My fleet name");
			$("p span.totalSelections").empty().append("0");
			
			$("#dashboardBuilder .fleetsDropdown li.selected").removeClass("show").removeClass("selected");
		
		}
		

	});
	
	/* window scroll */
	$(window).scroll(function(e){
	
		var s = $(window).scrollTop();
		var hero = $("#hero");
		

		/* hero image parallax */
		hero.css({"background-position":"0 -"+(s/4)+"px"});
		hero.find(".container").css({"top":"-"+(s/3)+"px"});
		
	
	});

	/* window resize */
	$(window).resize(function(e) {
        
		init();

	});
	
	//Reset the timer whenever the mouse moves or a key is pressed
	$(document.body).bind("mousemove keypress", function(e) {
			time = new Date().getTime();
	});

	/*
	* Main initialize function
	* 
	*/
	function init(){
		var sHeight = $(window).height();
		var sWidth = $(window).width();	
		
		if ($(".dashboardList .inner").length > 0) {

			$(".dashboardList .inner").sortable({
				handle: '.drag'
			});
			$(".dashboardList .inner").disableSelection();		

		}
		

		$("#login").css({"height":$(window).height()});
	
		//refresh function
		if ($("#canvassdiv").length > 0) {
			setTimeout(refresh, 10000);
    }
		
	}
	
	/*
	 * 
	 *
	 */
	function refresh() {
		
		//refresh the page when the timeout is reached
		if(new Date().getTime() - time >= timeout){
				window.location.reload(true);
		}
		else{
				setTimeout(refresh, 10000);
		}
	}
	
});

