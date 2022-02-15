CREATE DEFINER=`root`@`%` PROCEDURE `viewRtsBetween`(lower int, upper int)
BEGIN
select 
r.rt_name as Route, 
r.rt_diff as Difficulty, 
u.user_name as Setter, 
r.rt_info as Info
from ROUTE as r 
inner join
USER as u 
on r.rt_setter_id = u.user_id
where r.rt_diff between lower and upper;
END