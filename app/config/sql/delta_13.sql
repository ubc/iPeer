 --
 -- Add indexes to searchable columns for evaluation tables
 --
 
 -- evaluation_mixevals
 ALTER TABLE `evaluation_mixevals` ADD INDEX `evaluator` (`evaluator`);
 ALTER TABLE `evaluation_mixevals` ADD INDEX `evaluatee` (`evaluatee`);
 ALTER TABLE `evaluation_mixevals` ADD INDEX `grp_event_id` (`grp_event_id`);
 ALTER TABLE `evaluation_mixevals` ADD INDEX `event_id` (`event_id`);
 
 -- evaluation_rubrics
 ALTER TABLE `evaluation_rubrics` ADD INDEX `evaluator` (`evaluator`);
 ALTER TABLE `evaluation_rubrics` ADD INDEX `evaluatee` (`evaluatee`);
 ALTER TABLE `evaluation_rubrics` ADD INDEX `grp_event_id` (`grp_event_id`);
 ALTER TABLE `evaluation_rubrics` ADD INDEX `event_id` (`event_id`);
 
 -- evaluation_simples
 ALTER TABLE `evaluation_simples` ADD INDEX `evaluator` (`evaluator`);
 ALTER TABLE `evaluation_simples` ADD INDEX `evaluatee` (`evaluatee`);
 ALTER TABLE `evaluation_simples` ADD INDEX `grp_event_id` (`grp_event_id`);
 ALTER TABLE `evaluation_simples` ADD INDEX `event_id` (`event_id`);
