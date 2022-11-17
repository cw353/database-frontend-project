<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'dbClasses.php';

  $tables = array(
    'driver' => new Table('driver', 'Drivers', ['driverid'],
		[
      new Column('driverid', 'ID'),
      new Column('driverlicenseno', 'License #'),
      new Column('drivername', 'Name'),
      new Column('drivernickname', 'Nickname'),
      new Column('bonus', 'Bonus'),
    ]),
		'truck' => new Table('truck', 'Trucks', ['truckid'], [
			new Column('truckid', 'ID'),
			new Column('lpstate', 'License Plate State', null, Column::WRITE),
			new Column('lpnumber', 'License Plate Number', null, Column::WRITE),
			new Column('licenseplate', 'License Plate', null, Column::READ, "concat(lpstate, ' ', lpnumber)"),
			new Column('truckmodel', 'Model'),
			new Column('driverid', 'Driver ID', new ForeignKeyInfo('driver', 'driverid')),
			new Column('ownerid', 'Owner ID', new ForeignKeyInfo('driver', 'driverid')),
		]),
		'repairtechnician' => new Table('repairtechnician', 'Repair Technicians', ['rtid'], [
      new Column('rtid', 'ID'), 
			new Column('fullname', 'Name', null, Column::READ, "concat(rtfname, ' ', rtlname)"),
			new Column('rtfname', 'First Name', null, Column::WRITE),
			new Column('rtlname', 'Last Name', null, Column::WRITE),
		]),
		'repaircollaboration' => new Table('repaircollaboration', 'Repair Collaborations', ['rt1id', 'rt2id'], [
			new Column('rt1id', 'Repair Technician 1 ID', new ForeignKeyInfo('repairtechnician', 'rtid')),
			new Column('rt2id', 'Repair Technician 2 ID', new ForeignKeyInfo('repairtechnician', 'rtid')),
		]),
		'shift' => new Table('shift', 'Shifts', ['driverid', 'shiftstart'], [
      new Column('driverid', 'Driver ID', new ForeignKeyInfo('driver', 'driverid')), 
			new Column('shiftstart', 'Shift Start'), 
			new Column('shiftend', 'Shift End'), 
			new Column('shiftlength_in_hrs', 'Shift Length (Hours)', null, Column::READ, 'round(timestampdiff(minute, shiftstart, shiftend)/60, 2)'),
			new Column('shiftearnings', 'Shift Earnings', null, Column::READ, 'round(hourlypay * timestampdiff(minute, shiftstart, shiftend)/60, 2)'),
			new Column('hourlypay', 'Hourly Pay'),
		]),
    'truckrepairs' => new Table('truckrepairs', 'Truck Repairs', ['rtid','truckid'], [
      new Column('rtid', 'Repair Technician ID', new ForeignKeyInfo('repairtechnician', 'rtid')),
      new Column('truckid', 'Truck ID', new ForeignKeyInfo('truck', 'truckid')),
      new Column('repaircost', 'Repair Cost'),
    ]),
		'region' => new Table('region', 'Regions', ['regionid'], [
			new Column('regionid', 'ID'),
			new Column('regionname', 'Name'),
		]),
		'truckservice' => new Table('truckservice', 'Truck Service', ['truckid', 'regionid'], [
			new Column('truckid', 'Truck ID', new ForeignKeyInfo('truck', 'truckid')),
			new Column('regionid', 'Region ID', new ForeignKeyInfo('region', 'regionid')),
		]),
		'repairequipment' => new Table('repairequipment', 'Repair Equipment', ['equipmentid'], [
			new Column('equipmentid', 'ID'),
			new Column('equipmentname', 'Name'),
		]),
		'equipmentusage' => new Table('equipmentusage', 'Equipment Usage', ['rtid', 'equipmentid'], [
      new Column('rtid', 'Repair Technician ID', new ForeignKeyInfo('repairtechnician', 'rtid')),
			new Column('equipmentid', 'Equipment ID', new ForeignKeyInfo('repairequipment', 'equipmentid')),
		]),
		'product' => new Table('product', 'Products', ['productid'], [
			new Column('productid', 'ID'),
			new Column('productname', 'Name'),
		]),
		'supplier' => new Table('supplier', 'Suppliers', ['supplierid'], [
			new Column('supplierid', 'ID'),
			new Column('suppliername', 'Name'),
		]),
		'supplierphone' => new Table('supplierphone', 'Supplier Phone Numbers', ['supplierid', 'supplierphone'], [
			new Column('supplierid', 'Supplier ID', new ForeignKeyInfo('supplier', 'supplierid')),
			new Column('supplierphone', 'Phone Number'),
		]),
		'delivery' => new Table('delivery', 'Deliveries', ['regionid', 'productid', 'supplierid'], [
			new Column('regionid', 'Region ID', new ForeignKeyInfo('region', 'regionid')),
			new Column('productid', 'Product ID', new ForeignKeyInfo('product', 'productid')),
			new Column('supplierid', 'Supplier ID', new ForeignKeyInfo('supplier', 'supplierid')),
		]),
  );
?>
