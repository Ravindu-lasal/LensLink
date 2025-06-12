-- Add missing columns to payments table if they don't exist
ALTER TABLE payments ADD COLUMN IF NOT EXISTS payment_date DATETIME DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE payments ADD COLUMN IF NOT EXISTS payment_status varchar(20) DEFAULT 'pending';
ALTER TABLE payments ADD COLUMN IF NOT EXISTS is_purchased TINYINT(1) DEFAULT 0;
