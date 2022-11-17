<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'dbClasses.php';

  $tables = array(
    'driver' => new Table('driver', 'Drivers', ['driverid'],
		[
      new Column('driverid', 'ID', 'int'),
      new Column('driverlicenseno', 'License #', 'text'),
      new Column('drivername', 'Name', 'text'),
      new Column('drivernickname', 'Nickname', 'text'),
      new Column('bonus', 'Bonus', 'money'),
    ]),
		'truck' => new Table('truck', 'Trucks', ['truckid'], [
			new Column('truckid', 'ID', 'int'),
			new Column('lpstate', 'License Plate State', 'text', null, Column::WRITE),
			new Column('lpnumber', 'License Plate Number', 'text', null, Column::WRITE),
			new Column('licenseplate', 'License Plate', 'text', null, Column::READ, "concat(lpstate, ' ', lpnumber)"),
			new Column('truckmodel', 'Model', 'text'),
			new Column('driverid', 'Driver ID', 'int', new ForeignKeyInfo('driver', 'driverid')),
			new Column('ownerid', 'Owner ID', 'int', new ForeignKeyInfo('driver', 'driverid')),
		]),
		'repairtechnician' => new Table('repairtechnician', 'Repair Technicians', ['rtid'], [
      new Column('rtid', 'ID', 'int'), 
			new Column('fullname', 'Name', 'text', null, Column::READ, "concat(rtfname, ' ', rtlname)"),
			new Column('rtfname', 'First Name', 'text', null, Column::WRITE),
			new Column('rtlname', 'Last Name', 'text', null, Column::WRITE),
		]),
		'repaircollaboration' => new Table('repaircollaboration', 'Repair Collaborations', ['rt1id', 'rt2id'], [
			new Column('rt1id', 'Repair Technician 1 ID', 'int', new ForeignKeyInfo('repairtechnician', 'rtid')),
			new Column('rt2id', 'Repair Technician 2 ID', 'int', new ForeignKeyInfo('repairtechnician', 'rtid')),
		]),
		'shift' => new Table('shift', 'Shifts', ['driverid', 'shiftstartdate', 'shiftstarttime'], [
      new Column('driverid', 'Driver ID', 'int', new ForeignKeyInfo('driver', 'driverid')), 
			new Column('shiftstartdate', 'Shift Start Date', 'date'), 
			new Column('shiftstarttime', 'Shift Start Time', 'time'), 
			new Column('shiftenddate', 'Shift End Date', 'date'), 
			new Column('shiftendtime', 'Shift End Time', 'time'), 
			new Column('shiftlength_in_hrs', 'Shift Length (Hours)', 'numeric', null, Column::READ, "round(timestampdiff(minute, concat(shiftstartdate, ' ', shiftstarttime), concat(shiftenddate, ' ', shiftendtime))/60,2)"),
			new Column('shiftearnings', 'Shift Earnings', 'money', null, Column::READ, "round(hourlypay * timestampdiff(minute, concat(shiftstartdate, ' ', shiftstarttime), concat(shiftenddate, ' ', shiftendtime))/60,2)"),
			new Column('hourlypay', 'Hourly Pay', 'money'),
		]),
    'truckrepairs' => new Table('truckrepairs', 'Truck Repairs', ['rtid','truckid'], [
      new Column('rtid', 'Repair Technician ID', 'int', new ForeignKeyInfo('repairtechnician', 'rtid')),
      new Column('truckid', 'Truck ID', 'int', new ForeignKeyInfo('truck', 'truckid')),
      new Column('repairdate', 'Repair Date', 'date'),
      new Column('repaircost', 'Repair Cost', 'money'),
    ]),
		'region' => new Table('region', 'Regions', ['regionid'], [
			new Column('regionid', 'ID', 'int'),
			new Column('regionname', 'Name', 'text'),
		]),
		'truckservice' => new Table('truckservice', 'Truck Service', ['truckid', 'regionid'], [
			new Column('truckid', 'Truck ID', 'int', new ForeignKeyInfo('truck', 'truckid')),
			new Column('regionid', 'Region ID', 'int', new ForeignKeyInfo('region', 'regionid')),
		]),
		'repairequipment' => new Table('repairequipment', 'Repair Equipment', ['equipmentid'], [
			new Column('equipmentid', 'ID', 'int'),
			new Column('equipmentname', 'Name', 'text'),
		]),
		'equipmentusage' => new Table('equipmentusage', 'Equipment Usage', ['rtid', 'equipmentid'], [
      new Column('rtid', 'Repair Technician ID', 'int', new ForeignKeyInfo('repairtechnician', 'rtid')),
			new Column('equipmentid', 'Equipment ID', 'int', new ForeignKeyInfo('repairequipment', 'equipmentid')),
		]),
		'product' => new Table('product', 'Products', ['productid'], [
			new Column('productid', 'ID', 'int'),
			new Column('productname', 'Name', 'text'),
		]),
		'supplier' => new Table('supplier', 'Suppliers', ['supplierid'], [
			new Column('supplierid', 'ID', 'int'),
			new Column('suppliername', 'Name', 'text'),
		]),
		'supplierphone' => new Table('supplierphone', 'Supplier Phone Numbers', ['supplierid', 'supplierphone'], [
			new Column('supplierid', 'Supplier ID', 'int', new ForeignKeyInfo('supplier', 'supplierid')),
			new Column('supplierphone', 'Phone Number', 'int'),
		]),
		'delivery' => new Table('delivery', 'Deliveries', ['regionid', 'productid', 'supplierid'], [
			new Column('regionid', 'Region ID', 'int', new ForeignKeyInfo('region', 'regionid')),
			new Column('productid', 'Product ID', 'int', new ForeignKeyInfo('product', 'productid')),
			new Column('supplierid', 'Supplier ID', 'int', new ForeignKeyInfo('supplier', 'supplierid')),
		]),
  );
?>
