library(plotly)

source("shared/database.r")
source("all/functions.r")

bolsonaroMapData <- getUsersMapData(c('jairbolsonaro', 'bolsonaro', 'bolsonaro2018', 'jairbolsonaropresidente', 'bolsonaropresidente'))

ciroMapData <- getUsersMapData(c('cirogomes2018', 'ciro2018', 'cirogomes', 'ciropresidente', 'cirogomespresidente'))

alckminMapData <- getUsersMapData(c('alckmin', 'geraldoalckmin', 'alckmin2018', 'alckminpresidente', 'geraldoalckminpresidente'))

marinaMapData <- getUsersMapData(c('marina2018', 'marinasilva', 'marinasilva2018', 'marinapresidente', 'cirogomespresidente'))

manuelaMapData <- getUsersMapData(c('manueladavila', 'manueladavila2018', 'manuela2018', 'manueladavilapresidente', 'manuelapresidente'))

amoedoMapData <- getUsersMapData(c('joaoamoedo', 'joaoamoedo2018', 'amoedo2018', 'joaoamoedopresidente', 'amoedopresidente', 'JoaoAmoedoNaJovemPan'))

geo <- list(
  scope = 'south america',
  showland = TRUE,
  landcolor = toRGB("gray95"),
  countrycolor = toRGB("gray80")
)

title <- paste('Localiza\u00e7\u00e3o dos usu\u00e1rios - Brasil - Maio/Junho - 2018')

p <- plot_geo(bolsonaroMapData, sizes = c(10, 300), width = 1024, height = 600) %>%
  add_markers(#bolsonaro
    x = ~longitude, y = ~latitude, size = ~quantidade, marker = list(color = 'green', symbol = 'circle'),
    text = ~paste(bolsonaroMapData$local, " - ", bolsonaroMapData$quantidade, ' usu\u00e1rio(s)'), name = paste('Jair Bolsonaro (', sum(bolsonaroMapData$quantidade), ' usu\u00e1rios)', sep = '')
  )%>%
  add_markers(#ciro
    x = ciroMapData$longitude, y = ciroMapData$latitude, size = ciroMapData$quantidade, marker = list(color = '#87CEEB', symbol = 'circle'),
    text = ~paste(ciroMapData$local, " - ", ciroMapData$quantidade, ' usu\u00e1rio(s)'), name = paste('Ciro Gomes (', sum(ciroMapData$quantidade), ' usu\u00e1rios)', sep = '')
  )%>%
  add_markers(#alckmin
    x = alckminMapData$longitude, y = alckminMapData$latitude, size = alckminMapData$quantidade, marker = list(color = 'blue', symbol = 'circle'),
    text = ~paste(alckminMapData$local, " - ", alckminMapData$quantidade, ' usu\u00e1rio(s)'), name = paste('Geraldo Alckmin (', sum(alckminMapData$quantidade), ' usu\u00e1rios)', sep = '')
  )%>%
  add_markers(#marina silva
    x = marinaMapData$longitude, y = marinaMapData$latitude, size = marinaMapData$quantidade, marker = list(color = 'yellow', symbol = 'circle'),
    text = ~paste(marinaMapData$local, " - ", marinaMapData$quantidade, ' usu\u00e1rio(s)'), name = paste('Marina Silva (', sum(marinaMapData$quantidade), ' usu\u00e1rios)', sep = '')
  )%>%
  add_markers(#manuela davila
    x = manuelaMapData$longitude, y = manuelaMapData$latitude, size = manuelaMapData$quantidade, marker = list(color = 'red', symbol = 'circle'),
    text = ~paste(manuelaMapData$local, " - ", manuelaMapData$quantidade, ' usu\u00e1rio(s)'), name = paste('Manuela D\'\u00c1vila (', sum(manuelaMapData$quantidade), ' usu\u00e1rios)', sep = '')
  )%>%
  add_markers(#joao amoedo
    x = amoedoMapData$longitude, y = amoedoMapData$latitude, size = amoedoMapData$quantidade, marker = list(color = 'orange', symbol = 'circle'),
    text = ~paste(amoedoMapData$local, " - ", amoedoMapData$quantidade, ' usu\u00e1rio(s)'), name = paste('Jo\u00e3o Am\u00f4edo (', sum(amoedoMapData$quantidade), ' usu\u00e1rios)', sep = '')
  )%>%
  layout(title = title, geo = geo)