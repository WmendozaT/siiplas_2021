select *
                from unidad_actividad ua
                Inner Join _distritales as dist On dist.dist_id=ua.dist_id
                Inner Join _departamentos as dep On dep.dep_id=dist.dep_id
                Inner Join tipo_ubicacion as tu On ua.tu_id=tu.tu_id
                Inner Join v_tp_establecimiento as te On ua.te_id=te.te_id
                Inner Join estado_unidad as eu On ua.eu_id=eu.eu_id
                Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                where dep.dep_id=5 and ua.act_estado!=3 and tu.estado!=0 and te.estado!=0 and eu.estado!=0 and ug.g_id=2020 and ua.te_id!=21
                order by dist.dist_id,te.te_id,ua.act_id asc




                select *
                from unidad_actividad ua
                Inner Join _distritales as dist On dist.dist_id=ua.dist_id
                Inner Join _departamentos as dep On dep.dep_id=dist.dep_id
                Inner Join tipo_ubicacion as tu On ua.tu_id=tu.tu_id
                Inner Join v_tp_establecimiento as te On ua.te_id=te.te_id
                Inner Join estado_unidad as eu On ua.eu_id=eu.eu_id
                Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                where dist.dist_id=7 and ua.act_estado!=3 and tu.estado!=0 and te.estado!=0 and eu.estado!=0 and ug.g_id=2020 and ua.te_id!=21
                order by dist.dist_id,te.te_id,ua.act_id asc






                select *
                from vista_componentes_dictamen
                where proy_id=1781
                ORDER BY com_id  asc




                select cpoa.proy_id,SUM(vmonto.sum)
                from certificacionpoa cpoa
                Inner Join vmonto_certificado_por_cpoa as vmonto On vmonto.cpoa_id=cpoa.cpoa_id
                where cpoa.proy_id=1781 and cpoa.cpoa_gestion=2020
                group by cpoa.proy_id



                select *
                from vdatos_totales_certificacion_poa



                select *
                from vmonto_certificado_por_cpoa
                