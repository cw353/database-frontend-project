<!-- Information about tables in the database -->
<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'dbClasses.php';

	// associative array of Table objects, each representing a table in the database, with the table name as the key
  $tables = array(
		'delivery' => new Table('delivery', 'Deliveries', ['regionid', 'productid', 'supplierid'], [
			new Column('regionid', 'Region ID', 'int', new ForeignKeyInfo('region', 'regionid')),
			new Column('productid', 'Product ID', 'int', new ForeignKeyInfo('product', 'productid')),
			new Column('supplierid', 'Supplier ID', 'int', new ForeignKeyInfo('supplier', 'supplierid')),
		]),
    'driver' => new Table('driver', 'Drivers', ['driverid'],
		[
      new Column('driverid', 'ID', 'id'),
      new Column('driverlicenseno', 'License #', 'text'),
      new Column('drivername', 'Name', 'text'),
      new Column('drivernickname', 'Nickname', 'text', null, Column::READ|Column::WRITE|Column::OPTIONAL),
      new Column('bonus', 'Bonus', 'money'),
    ]),
		'equipmentusage' => new Table('equipmentusage', 'Equipment Usage', ['rtid', 'equipmentid'], [
      new Column('rtid', 'Repair Technician ID', 'id', new ForeignKeyInfo('repairtechnician', 'rtid')),
			new Column('equipmentid', 'Equipment ID', 'id', new ForeignKeyInfo('repairequipment', 'equipmentid')),
		]),
		'product' => new Table('product', 'Products', ['productid'], [
			new Column('productid', 'ID', 'id'),
			new Column('productname', 'Name', 'text'),
		]),
		'region' => new Table('region', 'Regions', ['regionid'], [
			new Column('regionid', 'ID', 'id'),
			new Column('regionname', 'Name', 'text'),
		]),
		'repaircollaboration' => new Table('repaircollaboration', 'Repair Collaborations', ['rt1id', 'rt2id'], [
			new Column('rt1id', 'Repair Technician 1 ID', 'id', new ForeignKeyInfo('repairtechnician', 'rtid')),
			new Column('rt2id', 'Repair Technician 2 ID', 'id', new ForeignKeyInfo('repairtechnician', 'rtid')),
		]),
		'repairequipment' => new Table('repairequipment', 'Repair Equipment', ['equipmentid'], [
			new Column('equipmentid', 'ID', 'id'),
			new Column('equipmentname', 'Name', 'text'),
		]),
		'repairtechnician' => new Table('repairtechnician', 'Repair Technicians', ['rtid'], [
      new Column('rtid', 'ID', 'id'), 
			new Column('fullname', 'Name', 'text', null, Column::READ, "concat(rtfname, ' ', rtlname)"),
			new Column('rtfname', 'First Name', 'text', null, Column::WRITE),
			new Column('rtlname', 'Last Name', 'text', null, Column::WRITE),
		]),
		'shift' => new Table('shift', 'Shifts', ['driverid', 'shiftstartdate', 'shiftstarttime'], [
      new Column('driverid', 'Driver ID', 'id', new ForeignKeyInfo('driver', 'driverid')), 
			new Column('shiftstartdate', 'Shift Start Date', 'date'), 
			new Column('shiftstarttime', 'Shift Start Time', 'time'), 
			new Column('shiftenddate', 'Shift End Date', 'date'), 
			new Column('shiftendtime', 'Shift End Time', 'time'), 
			new Column('shiftlength_in_hrs', 'Shift Length (Hours)', 'numeric', null, Column::READ, "round(timestampdiff(minute, concat(shiftstartdate, ' ', shiftstarttime), concat(shiftenddate, ' ', shiftendtime))/60,2)"),
			new Column('shiftearnings', 'Shift Earnings', 'money', null, Column::READ, "round(hourlypay * timestampdiff(minute, concat(shiftstartdate, ' ', shiftstarttime), concat(shiftenddate, ' ', shiftendtime))/60,2)"),
			new Column('hourlypay', 'Hourly Pay', 'money'),
		]),
		'supplier' => new Table('supplier', 'Suppliers', ['supplierid'], [
			new Column('supplierid', 'ID', 'id'),
			new Column('suppliername', 'Name', 'text'),
		]),
		'supplierphone' => new Table('supplierphone', 'Supplier Phone Numbers', ['supplierid', 'supplierphone'], [
			new Column('supplierid', 'Supplier ID', 'id', new ForeignKeyInfo('supplier', 'supplierid')),
			new Column('supplierphone', 'Phone Number', 'text'),
		]),
		'truck' => new Table('truck', 'Trucks', ['truckid'], [
			new Column('truckid', 'ID', 'id'),
			new Column('lpstate', 'License Plate State', 'text', null, Column::WRITE),
			new Column('lpnumber', 'License Plate Number', 'text', null, Column::WRITE),
			new Column('licenseplate', 'License Plate', 'text', null, Column::READ, "concat(lpstate, ' ', lpnumber)"),
			new Column('truckmodel', 'Model', 'text'),
			new Column('driverid', 'Driver ID', 'id', new ForeignKeyInfo('driver', 'driverid')),
			new Column('ownerid', 'Owner ID', 'id', new ForeignKeyInfo('driver', 'driverid')),
		]),
    'truckrepairs' => new Table('truckrepairs', 'Truck Repairs', ['rtid','truckid'], [
      new Column('rtid', 'Repair Technician ID', 'id', new ForeignKeyInfo('repairtechnician', 'rtid')),
      new Column('truckid', 'Truck ID', 'id', new ForeignKeyInfo('truck', 'truckid')),
      new Column('repairdate', 'Repair Date', 'date'),
      new Column('repaircost', 'Repair Cost', 'money'),
    ]),
		'truckservice' => new Table('truckservice', 'Truck Service', ['truckid', 'regionid'], [
			new Column('truckid', 'Truck ID', 'id', new ForeignKeyInfo('truck', 'truckid')),
			new Column('regionid', 'Region ID', 'id', new ForeignKeyInfo('region', 'regionid')),
		]),
  );
?>
