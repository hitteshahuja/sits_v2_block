M.sits_v2 = M.sits_v2 || {};
M.sits_v2.blockname = 'sits_v2';
M.sits_v2.SITS_NETWORKSTATUS = 0;
M.sits_v2.sURL = M.cfg.wwwroot + '/blocks/' + 'sits_v2/' + 'async_request.php'; 
M.sits_v2.init = function(Y){
	 
YUI().use('anim','transition', function(Y,tabs) {
	
	//Check whether SITS is online or not
	M.sits_v2.aSyncRequest('network_status',null);
    var anim = new Y.Anim({
    	node:'#course_tabs .yui3-bd',
    	to :{height:'500px',display : 'block',opacity : 0}
    });
        var onClick = function(e){
        M.sits_v2.getMappingsforCourse(3);
    	e.preventDefault();
    	//Get mappings for that course
    	anim.run();
    }
    Y.all('#course_containers .sits_course_title').on('click',onClick);
});
}
M.sits_v2.addCohort = function(){
	
	//Append a new div with the add-cohort form
	M.sits_v2.getAddCohortForm();
	//Add YUI Panel
	//formSrc.setStyle('display','block');
 	/*YUI().use('panel','dd-plugin', function(Y,tabs) {
	var AddCohortForm = new Y.Panel({
		visible: false,
		render :true,
		modal : true,
		xy : [100,125],
		headerContent : 'Add New Cohort',
		srcNode : '#sits_add_cohort_form_3365',
		width      : 400,
		plugins : [Y.Plugin.Drag]
	}).render();
	AddCohortForm.show();
});*/
}
M.sits_v2.getAddCohortForm = function(){
	//Create a new form
	var addcohorform = Y.one('.sits_add_cohort_form').cloneNode(true);
	console.log(addcohorform);
	Y.one('.sits_course_mappings_area').append(Y.Node.create('<div id="myshit">'+addcohorform.getHTML()+'<div>'));
}
//This will just fetch the mapping ids
M.sits_v2.getMappingsforCourse = function(courseid){
	var params = {'courseid' : courseid,'function' : 'get_mappings_for_course2' };
	var response = this.aSyncRequest('get_mappings_for_course',params);	
}
M.sits_v2.displayMapping = function (mapping){
	 
		var courseid = mapping.courseid;
		var sits_code = mapping.cohort.sits_code;
		var period_code = mapping.cohort.period_code
		var ac_year = mapping.cohort.academic_year;
		var start_date = mapping.start.date;
		var end_date = mapping.end.date;
		
		//Transistion
		var transitionObject = function (obj) {
    obj.transition({
        duration: 2,
        easing: 'ease-in',
        on: {
        start: function() {
        	},
        end: function() {
        		 
       		 }
        },
        height:obj.getStyle('height'),
        opacity: {
            duration: 1.2,
            delay :1,
            value: 1
        }
    });
}
		//Build the mapping area
		var mapping_html = '<div class="sits_mapping_box" id ="'+mapping.id+'">';
		mapping_html += '<span>SITS Code:<strong>'+sits_code+'</strong></span>';
		mapping_html += '<span>Academic Year:<strong>'+ac_year+'</strong></span>';
		mapping_html += '<span>Period Code:<strong>'+period_code+'</strong></span>';
		mapping_html += '';
		mapping_html += '</div>';
		mappingNode = Y.Node.create(mapping_html);
		Y.one('.sits_course_mappings_container').prepend(mappingNode);
		//transitionObject(mappingNode);
		Y.one('.sits_course_mappings_controls').setStyle('display','block');

}
M.sits_v2.getMappingById = function(mapping_id){
	var params = {'mappingid': mapping_id,'function' : 'get_map'};
	var response = this.aSyncRequest('get_mapping_by_id',params);
}
M.sits_v2.aSyncRequest = function (action,parameters){
	var refreshicon = M.util.image_url('arrow-circle-double', 'block_' + M.sits_v2.blockname);
	M.sits_v2.aSyncRequest.onStart = function () {
    	//Event handler called when the transaction begins
        //We show the loader image
        var loaderGIF = M.util.image_url('network_ajax_loader', 'block_' + M.sits_v2.blockname);
        //Y.one('#sits_network_refresh img').set('src',loaderGIF);
        }
	switch(action){
		case 'get_mapping_by_id' : 
		responseSuccess = function (id, response, data){
			var JSONresponse = JSON.parse(response.responseText);
			M.sits_v2.displayMapping(JSONresponse);
		}
		break;
		case 'get_mappings_for_course' :
		responseSuccess = function (id, response, data){
			var JSONresponse = JSON.parse(response.responseText);
			//Once we have the mapping ids,get the mapping object for each ID
			var mappingids = JSONresponse;
			for(i=0;i<mappingids.length;i++){
				
				M.sits_v2.getMappingById(mappingids[i]);
			}
			
			//M.sits_v2.displayMappings(JSONresponse);
		}
		break;
		case 'network_status':
		responseSuccess = function(id,response,data){
			M.sits_v2.SITSNetworkStatus(response) ;
		}
		break;
	}
	
	var cfg = {
		method : 'POST',
		data : {'params' :JSON.stringify(parameters) },
		on :{
			start : M.sits_v2.aSyncRequest.onStart,
			complete : responseSuccess,
			end: function(){}
			//end : function(){Y.one('#sits_network_refresh img').set('src',refreshicon)}
		}
	};
	//Start the transaction
	var request = Y.io(M.sits_v2.sURL,cfg);
}
M.sits_v2.SITSNetworkStatus = function(response){
	var onlineIcon = M.util.image_url('network-status', 'block_' + M.sits_v2.blockname);
	var offlineIcon = M.util.image_url('network-status-offline', 'block_' + M.sits_v2.blockname);
	if(response == null){
		// We are sending a request
		M.sits_v2.aSyncRequest('network_status',null);
	}
	else{
	var network_status = response.responseText;
	if(network_status == '1')
	{
		//Clear previous status
		//We are online
		//Change the status icon to online
		Y.one('.network_text').empty().append('SITS is online!'); //Adding the text
		Y.one('#sits_v2_network_status').replaceClass('sits_offline','sits_online'); //for background class
	}
	else{
		//We are offline
		Y.one('#sits_v2_network_status').replaceClass('sits_online','sits_offline');
		//Change the status icon to offline
		Y.one('.network_text').empty().append('SITS is offline!'); //Adding the text
	}
	//Update the response on the interface
	//Y.one('#sits_v2_network_status').append(response.responseText);
	}
	
}

M.sits_v2.get_acyear_options = function (isbackward) {
	var currentDate = new Date();
	if(isbackward === true){
		var startDate =  currentDate.getFullYear() - 3; // Go only 3 years back
    var endDate = currentDate.getFullYear();
	}
	else{
		var startDate =  currentDate.getFullYear(); // Go one year forward
    var endDate = currentDate.getFullYear() + 1;
	}
	
	var html = '';
	for( i = startDate; i <= endDate ; i++ ){
	    var startdate_short = i.toString().substring(3,4);
	    var nextyear_short =  parseInt(startdate_short) + 1;
	    var academic_year = i + "/" + nextyear_short;
	    var selected = '';
	    if( i == currentDate.getFullYear()){
	    	selected = 'selected ="selected"';
	    }
	    html += '<option value="'+academic_year + '" '+ selected + '>'+academic_year+'</option>';
	}
	return html;
};
