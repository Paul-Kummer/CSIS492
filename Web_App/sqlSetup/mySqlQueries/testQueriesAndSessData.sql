call addRoute('blue', '3', '1', 'this is a test');
call addSession('15', '6', 'I climbed route 6');
call changeRtGrade('1', '6');
call changeRtInfo('1', 'I changed this');
call getSessionsByUser('10');
call sp_makeUser('Paul Kummer', 'climbtime');
call updatePass('2', 'climbtime', 'time');
call validPassword('6', 'bob');
call validPassword('paul', 'climbtime');
call viewRtsBetween('0', '5');
call getUserIdFromName('1');
call getRouteIDAndName();
call getMaxClimb('4');
call getLastSessionDate('4');
call numOfClimbsByUID('4');
call userAvgDiffPerMonth('1');



select user_id, user_name from USER order by user_name asc;

select * from USER;
select * from ROUTE;
select * from SESSION;


select MONTHNAME(sess_date) 'Month', avg(rt_diff) 'Average V Grade'
from SESSION s
left join ROUTE r
on r.rt_id = s.rt_id
where s.usr_id = '1'
group by YEAR(sess_date), MONTHNAME(sess_date)
order by YEAR(sess_date) desc, MONTHNAME(sess_date) desc;

truncate table SESSION;
insert into SESSION(rt_id, usr_id, sess_date)
values
('11','1',DATE('2020-12-24 00:00:00')),
('12','1',DATE('2020-12-24 00:00:00')),
('9','1',DATE('2020-12-24 00:00:00')),
('5','1',DATE('2020-12-24 00:00:00')),
('6','1',DATE('2020-12-24 00:00:00')),
('11','2',DATE('2020-12-24 00:00:00')),
('12','2',DATE('2020-12-24 00:00:00')),
('9','2',DATE('2020-12-24 00:00:00')),
('5','2',DATE('2020-12-24 00:00:00')),
('6','2',DATE('2020-12-24 00:00:00')),
('8','2',DATE('2020-12-24 00:00:00')),
('3','2',DATE('2020-12-24 00:00:00')),
('4','2',DATE('2020-12-24 00:00:00')),
('9','1',DATE('2020-12-29 00:00:00')),
('8','1',DATE('2020-12-29 00:00:00')),
('13','1',DATE('2020-12-29 00:00:00')),
('2','1',DATE('2020-12-29 00:00:00')),
('11','2',DATE('2020-12-29 00:00:00')),
('12','2',DATE('2020-12-29 00:00:00')),
('10','2',DATE('2020-12-29 00:00:00')),
('9','2',DATE('2020-12-29 00:00:00')),
('5','2',DATE('2020-12-29 00:00:00')),
('6','2',DATE('2020-12-29 00:00:00')),
('13','2',DATE('2020-12-29 00:00:00')),
('10','1',DATE('2020-12-02 00:00:00')),
('14','1',DATE('2020-12-02 00:00:00')),
('8','1',DATE('2020-12-02 00:00:00')),
('11','2',DATE('2020-12-02 00:00:00')),
('12','2',DATE('2020-12-02 00:00:00')),
('10','2',DATE('2020-12-02 00:00:00')),
('9','2',DATE('2020-12-02 00:00:00')),
('14','2',DATE('2020-12-02 00:00:00')),
('5','2',DATE('2020-12-02 00:00:00')),
('6','2',DATE('2020-12-02 00:00:00')),
('8','2',DATE('2020-12-02 00:00:00')),
('3','2',DATE('2020-12-02 00:00:00')),
('2','2',DATE('2020-12-02 00:00:00')),
('4','2',DATE('2020-12-02 00:00:00'));