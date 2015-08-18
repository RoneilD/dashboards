ALTER TABLE `rate_details`
	ADD COLUMN `truck_insurance_percent` double(10,4) NOT NULL DEFAULT '0.0000', 
	ADD COLUMN `trailer_insurance_percent` double(10,4) NOT NULL DEFAULT '0.0000' 
;