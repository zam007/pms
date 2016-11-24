alter table `sheet` rename `sheet_log`;
alter table `answer_sheet` rename `exam`;
alter table `classify_sheet` rename `sheet`;

ALTER TABLE `exam` CHANGE `answer_sheet_id` `exam_id` int(10) UNSIGNED AUTO_INCREMENT;
ALTER TABLE `sheet` CHANGE `classify_sheet_id` `sheet_id` int(10) UNSIGNED AUTO_INCREMENT;
ALTER TABLE `sheet` CHANGE `answer_sheet_id` `exam_id` int(10);

alter table `sheet` add `father_id` int(10) not null default '0' after `classify_id`;
ALTER TABLE `sheet_log` CHANGE `answer_sheet_id` `exam_id` int(10);
ALTER TABLE `sheet_log` CHANGE `sheet_id` `log_id` int(10) UNSIGNED AUTO_INCREMENT;
ALTER TABLE `sheet_log` CHANGE `classify_sheet_id` `sheet_id` int(10);
alter table `sheet_log` add `classify_id` int(10) not null default '0' after `sheet_id`;
alter table `sheet_log` add `father_id` int(10) not null default '0' after `classify_id`;