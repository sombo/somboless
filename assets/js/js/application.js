String.prototype.trim = function () {
    	return this.replace(/^\s*/, "").replace(/\s*$/, "");
	 }
	
 	 $(function(){

 			$(".del").click(function(){
				// alert("vlv");
 				var id = $(this).parent().parent().attr("id");
 				var thiss = $(this).parent().parent();
 				//$(this).parent().remove();
				var url = window.location.href;
				var urlSplit = url.split("/"); 
				var controller = "/copier/controllers/"+urlSplit[urlSplit.length-2]+"_controller/"+id;
 				$.ajax({
			
					url: controller,
 					data:"delete="+id,
 					type:'POST',
 					dataType:'text',
 					success:function(msg){
						if(msg.trim() == "ok")
 							thiss.remove();
 						
						else
							console.log(msg);
 					},
 				});
 				
 				return false;
 			});
 	 });