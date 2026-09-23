ALTER TABLE booking
  ADD COLUMN total_amount INT NOT NULL DEFAULT 0 AFTER STATUS,
  ADD COLUMN deposit_amount INT NOT NULL DEFAULT 0 AFTER total_amount,
  ADD COLUMN deposit_status VARCHAR(20) NOT NULL DEFAULT 'pending' AFTER deposit_amount;
