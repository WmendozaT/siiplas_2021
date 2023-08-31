------ Presupuesto Programado al Trimestre (Total)
select i.aper_id, SUM(t.monto) ppto_programado
from insumos i
Inner Join 
(
select ins_id,g_id, SUM(ipm_fis) monto
from temporalidad_prog_insumo
where (mes_id>0 and mes_id<=3) and g_id=2020
group by ins_id, g_id
)
as t On t.ins_id=i.ins_id
where i.aper_id=10624 and i.ins_estado!=3
group by i.aper_id


---- Monto Certificado al Trimestre

select cpoa.proy_id, SUM(tc.monto) ppto_certificado
from certificacionpoa cpoa
Inner Join certificacionpoadetalle as cpoad On cpoad.cpoa_id=cpoa.cpoa_id
Inner Join 
(
select t.ins_id,t.g_id, SUM(t.ipm_fis) monto
from cert_temporalidad_prog_insumo ct
Inner Join temporalidad_prog_insumo as t On t.tins_id=ct.tins_id
where (t.mes_id>0 and t.mes_id<=3) and t.g_id=2020
group by t.ins_id, t.g_id
) 
as tc On tc.ins_id=cpoad.ins_id
where cpoa.cpoa_estado!=3 and cpoa.proy_id=1781
group by cpoa.proy_id



------ Partida 1000 por defecto Certificado al trimestre
select i.aper_id, SUM(t.monto) ppto_programado
from insumos i
Inner Join partidas as p On p.par_id=i.par_id
Inner Join 
(
select ins_id,g_id, SUM(ipm_fis) monto
from temporalidad_prog_insumo
where (mes_id>0 and mes_id<=3) and g_id=2020
group by ins_id, g_id
)
as t On t.ins_id=i.ins_id
where i.aper_id=10624 and i.ins_estado!=3 and p.par_depende=10000
group by i.aper_id
