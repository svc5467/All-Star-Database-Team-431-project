SELECT *
FROM (	
	SELECT *
	FROM (
		SELECT *  
		FROM Sellers  
		WHERE login_name='Irene' LIMIT 1
	) AS sellers_query
	INNER JOIN Users users
	ON users.user_id = sellers_query.seller_id
) AS sellers_users_together
INNER JOIN Addresses ads
ON ads.address_id = sellers_users_together.address_id