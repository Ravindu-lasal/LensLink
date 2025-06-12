-- Add is_purchased column to payments table
ALTER TABLE payments ADD COLUMN is_purchased TINYINT(1) DEFAULT 0;

-- Update existing completed payments to be marked as purchased
UPDATE payments SET is_purchased = 1 WHERE payment_status = 'completed';
