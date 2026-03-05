-- Create reviews table for Tastelibmanan
-- This table stores business reviews from users

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fbowner_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reviewer_name` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `comment` text DEFAULT NULL,
  `photo` varchar(500) DEFAULT NULL,
  `video` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fbowner_id` (`fbowner_id`),
  KEY `user_id` (`user_id`),
  KEY `rating` (`rating`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add foreign key constraints (optional, remove if fb_owner or accounts tables don't exist yet)
-- ALTER TABLE `reviews` 
--   ADD CONSTRAINT `reviews_fbowner_fk` FOREIGN KEY (`fbowner_id`) REFERENCES `fb_owner` (`fbowner_id`) ON DELETE CASCADE,
--   ADD CONSTRAINT `reviews_user_fk` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`user_id`) ON DELETE CASCADE;
