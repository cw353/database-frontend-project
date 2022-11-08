<?php
  $tables = array(
    'driver' => new Table('driver', 'Drivers', [
      new Column('driverid', 'ID', 'driver'),
      new Column('driverlicenseno', 'License #', 'driver'),
      new Column('drivername', 'Name', 'driver'),
      new Column('bonus', 'Bonus', 'driver'),
    ]),
		'truck' => new Table('truck', 'Trucks', [
			new Column('truckid', 'ID', 'truck'),
			new Column('lpstate', 'License Plate State', 'truck'),
			new Column('lpnumber', 'License Plate Number', 'truck'),
			new Column('truckmodel', 'Model', 'truck'),
			new Column('driverid', 'Driver ID', 'driver'),
			new Column('ownerid', 'Owner ID', 'driver'),
		]),
		'repairtechnician' => new Table('repairtechnician', 'Repair Technicians', [
      new Column('rtid', 'ID', 'repairtechnician'),
			new Column('rtfname', 'First Name', 'repairtechnician'),
			new Column('rtlname', 'Last Name', 'repairtechnician'),
		]),
		'repaircollaboration' => new Table('repaircollaboration', 'Repair Collaborations', [
			new Column('rt1id', 'Repair Technician 1', 'repairtechnician'),
			new Column('rt2id', 'Repair Technician 2', 'repairtechnician'),
		]),
		'shift' => new Table('shift', 'Shift', [
      new Column('driverid', 'ID', 'driver'),
		]),
    'truckrepairs' => new Table('truckrepairs', 'Truck Repairs', [
      new Column('rtid', 'Repair Technician ID', 'repairtechnician'),
      new Column('truckid', 'Truck ID', 'truck'),
      new Column('repaircost', 'Repair Cost', 'truckrepairs'),
    ]),
  );
?>
