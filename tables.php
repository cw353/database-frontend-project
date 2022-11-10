<?php
	require_once 'dbClasses.php';

  $tables = array(
    'driver' => new Table('driver', 'Drivers', [
      new Column('driverid', 'ID'),
      new Column('driverlicenseno', 'License #'),
      new Column('drivername', 'Name'),
      new Column('bonus', 'Bonus'),
    ]),
		'truck' => new Table('truck', 'Trucks', [
			new Column('truckid', 'ID'),
			new Column('lpstate', 'License Plate State'),
			new Column('lpnumber', 'License Plate Number'),
			new Column('truckmodel', 'Model'),
			new Column('driverid', 'Driver ID', array('table' => 'driver', 'field' => 'driverid')),
			new Column('ownerid', 'Owner ID', array('table' => 'driver', 'field' => 'driverid')),
		]),
		'repairtechnician' => new Table('repairtechnician', 'Repair Technicians', [
      new Column('rtid', 'ID'), 
			new Column('fullname', 'Full Name', null, "concat(rtfname, ' ', rtlname)"),
			new Column('rtfname', 'First Name'),
			new Column('rtlname', 'Last Name'),
		]),
		'repaircollaboration' => new Table('repaircollaboration', 'Repair Collaborations', [
			new Column('rt1id', 'Repair Technician 1 ID', array('table' => 'repairtechnician', 'field' => 'rtid')),
			new Column('rt2id', 'Repair Technician 2 ID', array('table' => 'repairtechnician', 'field' => 'rtid')),
		]),
		'shift' => new Table('shift', 'Shifts', [
      new Column('driverid', 'Driver ID', array('table' => 'driver', 'field' => 'driverid')), 
			new Column('shiftstart_date', 'Shift Start Date', null, 'date(shiftstart)'),
			new Column('shiftstart_time', 'Shift Start Time', null, 'time(shiftstart)'),
			new Column('shiftend_date', 'Shift End Date', null, 'date(shiftend)'),
			new Column('shiftend_time', 'Shift End Time', null, 'time(shiftend)'),
			new Column('shiftlength_in_hrs', 'Shift Length (Hours)', null, 'round(timestampdiff(minute, shiftstart, shiftend)/60, 2)'),
			new Column('hourlypay', 'Hourly Pay'),
		]),
    'truckrepairs' => new Table('truckrepairs', 'Truck Repairs', [
      new Column('rtid', 'Repair Technician ID', array('table' => 'repairtechnician', 'field' => 'rtid')),
      new Column('truckid', 'Truck ID', array('table' => 'truck', 'field' => 'truckid')),
      new Column('repaircost', 'Repair Cost'),
    ]),
		'region' => new Table('region', 'Regions', [
			new Column('regionid', 'ID'),
			new Column('regionname', 'Name'),
		]),
		'truckservice' => new Table('truckservice', 'Truck Service', [
			new Column('truckid', 'Truck ID', array('table' => 'truck', 'field' => 'truckid')),
			new Column('regionid', 'Region ID', array('table' => 'region', 'field' => 'regionid')),
		]),
		'repairequipment' => new Table('repairequipment', 'Repair Equipment', [
			new Column('equipmentid', 'ID'),
			new Column('equipmentname', 'Name'),
		]),
		'equipmentusage' => new Table('equipmentusage', 'Equipment Usage', [
      new Column('rtid', 'Repair Technician ID', array('table' => 'repairtechnician', 'field' => 'rtid')),
			new Column('equipmentid', 'Equipment ID', array('table' => 'repairequipment', 'field' => 'equipmentid')),
		]),
		'product' => new Table('product', 'Products', [
			new Column('productid', 'ID'),
			new Column('productname', 'Name'),
		]),
		'supplier' => new Table('supplier', 'Suppliers', [
			new Column('supplierid', 'ID'),
			new Column('suppliername', 'Name'),
		]),
		'supplierphone' => new Table('supplierphone', 'Supplier Phone Numbers', [
			new Column('supplierid', 'Supplier ID', array('table' => 'supplier', 'field' => 'supplierid')),
			new Column('supplierphone', 'Phone Number'),
		]),
		'delivery' => new Table('delivery', 'Deliveries', [
			new Column('regionid', 'Region ID', array('table' => 'region', 'field' => 'regionid')),
			new Column('productid', 'Product ID', array('table' => 'product', 'field' => 'productid')),
			new Column('supplierid', 'Supplier ID', array('table' => 'supplier', 'field' => 'supplierid')),
		]),
  );
?>
