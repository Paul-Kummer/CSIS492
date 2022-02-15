CREATE PROCEDURE `changeRtGrade` (rtToChange int, newGrade int)
BEGIN
update ROUTE
set rt_diff = newGrade
where rt_id = rtToChange;
END
