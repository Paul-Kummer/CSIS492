DELIMITER ;;
CREATE  FUNCTION `verifyPass`(uName varchar(45), uPass varchar(45)) RETURNS varchar(5) CHARSET utf8
BEGIN
set @scramblePass = sha2(uPass,256);
set @dbPass = 
(
select user_pass from USER
where user_name like concat('%',uName,'%') || user_id = cast(uName as unsigned int)
limit 1
);

if 
@scramblePass <=> @dbPass then #compares null values too
return 'true';

else
return 'false';
end if;

END ;;
DELIMITER ;

DELIMITER ;;
CREATE FUNCTION `userExists`(tmpUser varchar(45)) RETURNS int(1)
BEGIN
set @numOfUsersWithName = 
(
select 
count(user_name) 
from USER
where user_name like tmpUser ||  
      user_id = cast(tmpUser as unsigned int)
limit 1
);

if 
cast(@numOfUsersWithName as int) > 0 then
return 0;

else
RETURN 1;
end if;

END
DELIMITER ;

DELIMITER ;;
CREATE  PROCEDURE `addRoute`(rtName varchar(45), rtDiff int, rtSetter int, rtInfo varchar(8000))
BEGIN
insert into ROUTE
(rt_name, rt_diff, rt_setter_id, rt_date, rt_info)
values
(
rtName,
rtDiff,
rtSetter,
curdate(),
rtInfo
);
END ;;
DELIMITER ;

DELIMITER ;;
CREATE  PROCEDURE `addSession`(uID int, rtID int, sessInfo varchar(8000))
BEGIN
insert into SESSION
(usr_id, rt_id, sess_date, sess_info)
values
(
uID,
rtID,
CURRENT_TIMESTAMP(),
sessInfo
);
END ;;
DELIMITER ;

DELIMITER ;;
CREATE  PROCEDURE `changeRtGrade`(rtToChange int, newGrade int)
BEGIN
update ROUTE
set rt_diff = newGrade
where rt_id = rtToChange;
END ;;
DELIMITER ;

DELIMITER ;;
CREATE  PROCEDURE `changeRtInfo`(rtToChange int, newInfo varchar(8000))
BEGIN
update ROUTE
set rt_info = newInfo
where rt_id = rtToChange;
END ;;
DELIMITER ;

DELIMITER ;;
CREATE  PROCEDURE `getLastSessionDate`(uID int)
BEGIN
select
sess_date 'Date'
from SESSION
where uID = usr_id
order by sess_date desc
limit 1;
END ;;
DELIMITER ;

DELIMITER ;;
CREATE  PROCEDURE `getMaxClimb`(uID int)
BEGIN
select 
r.rt_name as Name, 
r.rt_diff as Difficulty,
s.sess_date as Date
from SESSION s
left join ROUTE r on r.rt_id = s.rt_id
where uID = s.usr_id
order by r.rt_diff desc, s.sess_date desc
limit 1;
END ;;
DELIMITER ;

DELIMITER ;;
CREATE  PROCEDURE `getRouteIDAndName`()
BEGIN
select rt_id, rt_name
from ROUTE
order by rt_id asc;
END ;;
DELIMITER ;

DELIMITER ;;
CREATE  PROCEDURE `getSessionsByUser`(uID varchar(45))
BEGIN
select
DATE_FORMAT(s.sess_date,'%W, %b %D : %Y') as 'Date', 
r.rt_name as 'Route Name', 
r.rt_diff as 'Route Difficulty',
s.sess_info as 'Session Info'
From SESSION s 
inner join
	USER u 
	on s.usr_id = u.user_id
inner join
	ROUTE r 
	on  s.rt_id = r.rt_id
where s.usr_id = cast(uID as unsigned int)
order by s.sess_date desc ,r.rt_diff desc;


END ;;
DELIMITER ;

DELIMITER ;;
CREATE  PROCEDURE `getUserIdFromName`(uName varchar(45))
BEGIN
select user_id
from USER
where user_name like concat('%',uName,'%') || user_id = uName
limit 1;
END ;;
DELIMITER ;

DELIMITER ;;
CREATE  PROCEDURE `numOfClimbsByUID`(uID int)
BEGIN
select count(session_id)
from SESSION
where uID = usr_id;
END ;;
DELIMITER ;

DELIMITER ;;
CREATE  PROCEDURE `sp_makeUser`(uName varchar(45), uPass varchar(45))
BEGIN
insert into USER 
(user_name, user_pass)
Values
(
uName,
sha2(uPass,256)
);
END ;;
DELIMITER ;

DELIMITER ;;
CREATE  PROCEDURE `updatePass`(uID int, oldPass varchar(45), newPass varchar(45))
BEGIN
set @valid = verifyPass(uID, oldPass);

if @valid = 1 then
	update USER
	set user_pass = sha2(newPass,256)
	where 
	uID = user_id;
end if;

END ;;
DELIMITER ;

DELIMITER ;;
CREATE  PROCEDURE `userAvgDiffPerMonth`(uID varchar(45))
BEGIN
select MONTHNAME(sess_date) 'Month', avg(rt_diff) 'Average V Grade'
from SESSION s
left join ROUTE r
on r.rt_id = s.rt_id
where s.usr_id = cast(uID as unsigned int)
group by YEAR(sess_date), MONTHNAME(sess_date)
order by YEAR(sess_date) desc, MONTHNAME(sess_date) desc;
END ;;
DELIMITER ;

DELIMITER ;;
CREATE  PROCEDURE `validPassword`(uName varchar(45), uPass varchar(45))
BEGIN
select verifyPass(uName, uPass) as isValid;
END ;;
DELIMITER ;

DELIMITER ;;
CREATE  PROCEDURE `viewRtsBetween`(lower int, upper int)
BEGIN
select 
r.rt_name as Route, 
r.rt_diff as Difficulty, 
u.user_name as Setter, 
r.rt_info as Info
from ROUTE as r 
left join
USER as u 
on r.rt_setter_id = u.user_id
where r.rt_diff between lower and upper
order by r.rt_diff desc, u.user_name desc;
END ;;
DELIMITER ;