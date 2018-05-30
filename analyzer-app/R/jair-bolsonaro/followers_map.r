#map bolsonaro's followers
library(plotly)
library(magrittr)

source("database.r", local = TRUE)

res <- dbSendQuery(con, "SELECT latitude, longitude, COUNT(1) AS quantidade, local FROM V_LOCALIZACAO_TWEETS 
                  WHERE tweet LIKE '%#jairbolsonaro%' 
                  OR tweet LIKE '%#bolsonaro%'
                  OR tweet LIKE '%#bolsonaro2018%'
                  GROUP BY latitude, longitude, local
                  ORDER BY quantidade DESC")
data <- fetch(res, n = -1)

dataFrame <- data.frame("longitude" = data$LONGITUDE, "latitude" = data$LATITUDE, "local" = data$LOCAL, "quantidade" = data$quantidade)

dataFrame$q <- with(dataFrame, cut(quantidade, breaks = c(0, 10, 150, 300)))
levels(dataFrame$q) <- paste(c("0 - 10", "10 - 150", "150 - 300"), "seguidor(es)")
dataFrame$q <- as.ordered(dataFrame$q)

geo <- list(
  scope = 'south america',
  showland = TRUE,
  landcolor = toRGB("gray95"),
  countrycolor = toRGB("gray80")
)

p <- plot_geo(dataFrame, sizes = c(5, 300), width = 1024, height = 600) %>%
  add_markers(
    x = ~longitude, y = ~latitude, size = ~quantidade, color = ~q,
    text = ~paste(dataFrame$local, " - ", dataFrame$quantidade, 'seguidor(es)')
  )%>%
layout(title = 'Seguidores de Bolsonaro - Brasil - Maio/Junho - 2018', geo = geo)