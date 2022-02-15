CREATE PROCEDURE `getRouteIDAndName` ()
BEGIN
select rt_id, rt_name
from ROUTE
order by rt_id asc;
END
