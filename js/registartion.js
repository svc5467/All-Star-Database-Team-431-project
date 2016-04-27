		function register() {			
			alert("Register was called");
			
			var request;
			
			var i = 0;
			var table = 0;
			var x = document.getElementById("u_reg");
			
			// pull randomly generated IDs from PHP
			var seller_id = "<?php echo $seller_id; ?>";
			var address_id = <?php echo $address_id; ?>;
			
			// Queries for: Users, Sellers, Addresses (Respectively)
			var queries = ["INSERT INTO Users(user_id", "INSERT INTO Sellers(seller_id, address_id", "INSERT INTO Addresses (address_id"];
			var queries2 = [") VALUES (", ") VALUES (", ") VALUES ("];
			
			queries2[0] += "'" + seller_id + "'"; // "...) VALUES ($seller_id..."
			queries2[1] += "'" + seller_id + "', '" + address_id + "'"; // "...) VALUES ($seller_id, $address_id..."
			queries2[2] += "'" + seller_id + "'"; // "...) VALUES ($seller_id..."
					
			// pull user info from the form.
			for(i = 0; i < x.length; i++)
			{
				if(x.elements[i].title == "U") // add to user fields	
					table = 0;
				else if(x.elements[i].title == "S") // add to seller fields. all required, no need to null check.
					table = 1;
				else if (x.elements[i].title == 'A') // send to address table
					table = 2;
				else  // look at the three cases. USA! woo. haha.
					table = -1 // error, dont add to query.
				
				if (x.elements[i].value != "" && table >= 0)
				{
					queries[table] += ", " + x.elements[i].name;
					queries2[table] += "', '" + x.elements[i].value;
				}
			}
			
			// create queries
			var Uquery = queries[0] + queries2[0] + "');";
			var Squery = queries[1] + queries2[1] + "');";
			var Aquery = queries[2] + queries2[2] + "');";
			
			request = $.ajax({
				url: "http://localhost:8080/AllStarDB/registrationScript.php",
				type: "post",
				data: {q1:Uquery, q2:Squery, q3:Aquery},
				
				success: function(html){
					alert(html);
				},
				failure: function(html){
					alert(html);
				}
			});
		}