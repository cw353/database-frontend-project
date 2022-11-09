<?php
	require_once 'dbClasses.php';

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
			new Column('fullname', 'Full Name', 'repairtechnician', "concat(rtfname, ' ', rtlname)"),
			new Column('rtfname', 'First Name', 'repairtechnician'),
			new Column('rtlname', 'Last Name', 'repairtechnician'),
		]),
		'repaircollaboration' => new Table('repaircollaboration', 'Repair Collaborations', [
			new Column('rt1id', 'Repair Technician 1 ID', 'repairtechnician'),
			new Column('rt2id', 'Repair Technician 2 ID', 'repairtechnician'),
		]),
		'shift' => new Table('shift', 'Shifts', [
      new Column('driverid', 'Driver ID', 'driver'),
			new Column('shiftstart_date', 'Shift Start Date', 'shift', 'date(shiftstart)'),
			new Column('shiftstart_time', 'Shift Start Time', 'shift', 'time(shiftstart)'),
			new Column('shiftend_date', 'Shift End Date', 'shift', 'date(shiftend)'),
			new Column('shiftend_time', 'Shift End Time', 'shift', 'time(shiftend)'),
			new Column('shiftlength_in_hrs', 'Shift Length (Hours)', 'shift', 'round(timestampdiff(minute, shiftstart, shiftend)/60, 2)'),
			new Column('hourlypay', 'Hourly Pay', 'shift'),
		]),
    'truckrepairs' => new Table('truckrepairs', 'Truck Repairs', [
      new Column('rtid', 'Repair Technician ID', 'repairtechnician'),
      new Column('truckid', 'Truck ID', 'truck'),
      new Column('repaircost', 'Repair Cost', 'truckrepairs'),
    ]),
		'region' => new Table('region', 'Regions', [
			new Column('regionid', 'ID', 'region'),
			new Column('regionname', 'Name', 'region'),
		]),
		'truckservice' => new Table('truckservice', 'Truck Service', [
			new Column('truckid', 'Truck ID', 'truck'),
			new Column('regionid', 'Region ID', 'region'),
		]),
		'repairequipment' => new Table('repairequipment', 'Repair Equipment', [
			new Column('equipmentid', 'ID', 'repairequipment'),
			new Column('equipmentname', 'Name', 'repairequipment'),
		]),
		'equipmentusage' => new Table('equipmentusage', 'Equipment Usage', [
      new Column('rtid', 'Repair Technician ID', 'repairtechnician'),
			new Column('equipmentid', 'Equipment ID', 'repairequipment'),
		]),
		'product' => new Table('product', 'Products', [
			new Column('productid', 'ID', 'product'),
			new Column('productname', 'Name', 'product'),
		]),
		'supplier' => new Table('supplier', 'Suppliers', [
			new Column('supplierid', 'ID', 'supplier'),
			new Column('suppliername', 'Name', 'supplier'),
		]),
		'supplierphone' => new Table('supplierphone', 'Supplier Phone Numbers', [
			new Column('supplierid', 'ID', 'supplier'),
			new Column('supplierphone', 'Phone Number', 'supplierphone'),
		]),
		'delivery' => new Table('delivery', 'Deliveries', [
			new Column('regionid', 'Region ID', 'region'),
			new Column('productid', 'Product ID', 'product'),
			new Column('supplierid', 'Supplier ID', 'supplier'),
		]),
  );
?>
