drop database if exists modulo5;
create database if not exists modulo5;

use modulo5;

create table rol (
	rol_id int unsigned not null auto_increment,
	rol_nombre varchar(25) unique not null,
	rol_descripcion text,
	rol_created_at timestamp default current_timestamp,
	rol_updated_at timestamp default current_timestamp on update current_timestamp,
	primary key (rol_id)
);

create table departamento (
	departamento_id int unsigned not null auto_increment,
	departamento_nombre varchar(25) unique not null,
	departamento_descripcion text,
	departamento_created_at timestamp default current_timestamp,
	departamento_updated_at timestamp default current_timestamp on update current_timestamp,
	primary key (departamento_id)
);

create table usuario (
	usuario_id int unsigned not null auto_increment,
	usuario_username varchar(25) unique not null,
	usuario_password text not null,
	usuario_nombre varchar(100) not null,
	usuario_apellido varchar(100) not null,
	usuario_nacimiento date not null,
	usuario_dui char(9) unique not null,
	usuario_telefono char(8) unique not null,
	rol_id int unsigned not null,
	departamento_id int unsigned not null,
	usuario_created_at timestamp default current_timestamp,
	usuario_updated_at timestamp default current_timestamp on update current_timestamp,
	constraint fk_usuario_rol foreign key (rol_id) references rol (rol_id) on update cascade,
	constraint fk_usuario_departamento foreign key (departamento_id) references departamento (departamento_id) on update cascade,
	primary key (usuario_id)
);

create table log (
	log_id int unsigned not null auto_increment,
	log_modulo varchar(50) not null,
	log_descripcion text not null,
	usuario_id int unsigned not null,
	log_created_at timestamp default current_timestamp,
	constraint fk_log_usuario foreign key (usuario_id) references usuario (usuario_id) on update cascade,
	primary key (log_id)
);

create table institucion (
	institucion_id int unsigned not null auto_increment,
	institucion_nombre varchar(255) unique not null,
	institucion_created_by int unsigned not null,
	institucion_created_at timestamp default current_timestamp,
	constraint fk_institucion_usuario foreign key (institucion_created_by) references usuario (usuario_id) on update cascade	,
	primary key (institucion_id)
);

create table modalidad (
	modalidad_id int unsigned not null auto_increment,
	modalidad_nombre varchar(255) unique not null,
	modalidad_created_at timestamp default current_timestamp,
	primary key (modalidad_id)
);

create table capacitacion (
	capacitacion_id int unsigned not null auto_increment,
	capacitacion_nombre varchar(255) unique not null,
	institucion_id int unsigned not null,
	modalidad_id int unsigned not null,
	capacitacion_estatus enum('en proceso', 'reprobada', 'finalizada con diploma'),
	capacitacion_created_at timestamp default current_timestamp,
	capacitacion_updated_at timestamp default current_timestamp on update current_timestamp,
	constraint fk_capacitacion_institucion foreign key(institucion_id) references institucion(institucion_id),
	constraint fk_capacitacion_modalidad foreign key (modalidad_id) references modalidad(modalidad_id) on update cascade,
	primary key (capacitacion_id)
);

create table capacitacion_fechas (
	capacitacion_fechas_id int unsigned not null auto_increment,
	capacitacion_fechas_fecha date not null,
	capacitacion_fechas_created_at timestamp default current_timestamp,
	capacitacion_id int unsigned not null,
	primary key (capacitacion_fechas_id),
	constraint fk_capacitacion_fecha foreign key (capacitacion_id) references capacitacion (capacitacion_id) on delete cascade on update cascade
);

create table usuario_capacitacion (
	usuario_id int unsigned not null,
	capacitacion_id int unsigned not null,
	usuario_capacitacion_inscripcion text,
	usuario_capacitacion_diploma text,
	usuario_capacitacion_created_at timestamp default current_timestamp,
	constraint pk_usuario_capacitacion primary key(usuario_id, capacitacion_id),
	constraint fk_usuario_capacitacion foreign key(usuario_id) references usuario(usuario_id),
	constraint fk_capacitacion_usario foreign key(capacitacion_id) references capacitacion(capacitacion_id)
);

create table mision (
	mision_id int unsigned not null auto_increment,
	mision_nombre varchar(255) unique not null,
	mision_descripcion varchar(512) not null,
	mision_participantes varchar(512) not null,
	institucion_id int unsigned not null,
	mision_created_at timestamp default current_timestamp,
	mision_updated_at timestamp default current_timestamp on update current_timestamp,
	constraint fk_mision_institucion foreign key(institucion_id) references institucion(institucion_id),
	primary key (mision_id)
);

create table mision_fechas (
	mision_fechas_id int unsigned not null auto_increment,
	mision_fechas_fecha date not null,
	mision_id int unsigned not null,
	mision_fechas_created_at timestamp default current_timestamp,
	primary key(mision_fechas_id),
	constraint fk_mision_fecha foreign key(mision_id) references mision(mision_id) on delete cascade on update cascade
);

create table usuario_mision (
	usuario_id int unsigned not null,
	mision_id int unsigned not null,
	usuario_mision_comentarios text,
	created_at timestamp default current_timestamp,
	constraint pk_usuario_mision primary key(usuario_id, mision_id),
	constraint fk_usuario_mision foreign key(usuario_id) references usuario(usuario_id),
	constraint fk_mision_usario foreign key(mision_id) references mision(mision_id)
);

create table capacitacion_foto (
	capacitacion_foto_id int unsigned not null auto_increment,
	capacitacion_foto_url text not null,
	capacitacion_id int unsigned not null,
	capacitacion_foto_created_at timestamp default current_timestamp,
	constraint fk_capacitacion_foto foreign key (capacitacion_id) references capacitacion (capacitacion_id) on update cascade,
	primary key (capacitacion_foto_id)
);

create table mision_foto (
	mision_foto_id int unsigned not null auto_increment,
	mision_foto_url text not null,
	mision_id int unsigned not null,
	mision_foto_created_at timestamp default current_timestamp,
	constraint fk_mision_foto foreign key (mision_id) references mision (mision_id) on update cascade	,
	primary key (mision_foto_id)
);

insert into rol (rol_id, rol_nombre) values (1, 'Admin'), (2, 'Gerente'), (3, 'Empleado');

insert into departamento(departamento_id, departamento_nombre) values(1, 'Finanzas');

alter table capacitacion drop column capacitacion_estatus;

alter table usuario_capacitacion add column capacitacion_estatus enum('en proceso', 'reprobada', 'finalizada con diploma') default 'en proceso';